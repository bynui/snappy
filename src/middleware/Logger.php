<?php

namespace Middleware;

use Core\Middleware;
use Model\Logger as LoggerModel;
use Core\Utils;

class Logger extends Middleware{
    
    private $model = null;

    function __construct(){
        $this->model = new LoggerModel();
    }

    public function get($sequence){
        if ($sequence == "before"){
            $start = microtime(true);
            $this::setData("flag.start", $start);
            $this::setData("flag.loggerBefore", "before");
        }else{
            $end = microtime(true);
            $elapsed = $end - $this::getData("flag.start");
            $this::setData("flag.loggerAfter", "after");
            $this::setData("flag.end", $end);
            $this::setData("flag.elapsed", $elapsed);
            $schema = [
                ":route" => Utils::validateString( $this::getData("urls.route") ),
                ":endpoint" => Utils::validateString( $this::getData("urls.endpoint") )
            ];
            $this->model->setLog($schema);
        }
    }
}

?>