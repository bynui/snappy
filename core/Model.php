<?php

namespace Core;
use Core\Utils;
use Core\Config;
use Core\Traits\Log;
use PDOException;

abstract class Model extends \PDO{
    use Log;
    private $dbstr;
    private $result = [];
    private $statement = null;
    
    private function connectionString(string $dbdriver, string $dbhost, string $dbname, string $dbport): string{      
      $dblist = [
        "mysql" => "mysql:host=$dbhost;port=$dbport;dbname=$dbname;charset=utf8mb4",
        "postgresql" => "pgsql:host=$dbhost;port=$dbport;dbname=$dbname",
        "sqlserver" => "sqlsrv:Server=$dbhost,$dbport;Database=$dbname;ConnectionPooling=0"
      ];
      if (array_key_exists($dbdriver, $dblist)) return $dblist[$dbdriver];
      throw new \Core\Error\ErrorWrapper("Unknown database driver");
    }

    function __construct(){
      $dbstr = Config::getConfig("db");
      try{
        $opt = [
              parent::ATTR_ERRMODE => parent::ERRMODE_EXCEPTION,
              parent::ATTR_EMULATE_PREPARES => 1,
              parent::ATTR_PERSISTENT => 1,
              parent::ATTR_DEFAULT_FETCH_MODE => parent::FETCH_ASSOC
          ];
          parent::__construct(
            $this->connectionString(
              $dbstr["driver"],
              $dbstr["host"],
              $dbstr["name"],
              $dbstr["port"]
            ),
            $dbstr["user"], 
            $dbstr["pwd"],
            $opt
          );
      }catch(PDOException $e){
        throw new \Core\Error\ErrorWrapper($e);
      }
    }
    
    final protected function execute(string $sql, array $params = []): array{      
      try{
          $query = preg_replace("/\s+/", " ", trim($sql));
          $collection = [];
          $this->statement = parent::prepare( $query );
          if (!empty($params) && isset($params[0]) && is_array($params[0])) {
            foreach ($params as $key => $value) {
              $this->statement->execute($value);
              $collection = array_merge($collection, $this->statement->fetchAll());
            }
          }else{
            $this->statement->execute($params);
            $collection = $this->statement->fetchAll();
          }                  
          
          $this->result = [
            "status" => 200,
            "message" => Utils::responseHeader(200, true),
            "result" => $collection
          ];
          return $this->result;
      }catch(PDOException $e){
        throw new \Core\Error\ErrorWrapper($e);
      }
    }
}
?>
