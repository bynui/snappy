<?php

namespace Model;
use Core\Model;

class Logger extends Model{
    
    public function setLog($param): void{
        $sql = "insert into log(route, endpoint) values(:route, :endpoint)";
        $this->execute($sql, $param);
    }
}

?>