<?php

namespace Core;
use Core\Middleware;
use Core\Utils;
use Core\Config;
use Core\Callback;
use Core\Generator;

abstract class Controller extends Middleware {
    private static $result = [];

    final protected function map(array $arrayRoute): void{
        foreach ($arrayRoute as $route => $callback) {
            $paramKeys = [];
            $pattern = preg_replace_callback("/:([a-zA-Z_][a-zA-Z0-9_]*)/", function ($matches) use (&$paramKeys) {
                $paramKeys[] = $matches[1];
                return "([A-Za-z0-9._-]+)";
            }, $route);

            $fullPattern = "#^" . $this->getData("urls.base") . "/" . $this->getData("urls.controller");
            $pattern = trim($pattern, "/");
            if ($pattern !== "") {
                $fullPattern .= "/" . $pattern;
            }
            $fullPattern .= "$#iu";
            if (preg_match($fullPattern, $this->getData("urls.path"), $matches)) {
                $this->setData("urls.route", $route);
                array_shift($matches);                
                foreach ($paramKeys as $i => $key) {
                    $this->setData("keys.$key", $matches[$i]);
                }

                
                if (is_callable($callback)) {
                    $this->executeMiddlewares("before");
                    $result = call_user_func(function() use ($callback) {
                        $result = call_user_func($callback, Callback::class);
                        return $result;
                    });
                    $this->send($result);
                    return;
                }
            }
        }

        throw new \Core\Error\ErrorWrapper("Route is not found",404);
    }

    final protected function send(string $body): void {
        $acceptEncoding = $_SERVER["HTTP_ACCEPT_ENCODING"] ?? "";
        
        if (strpos($acceptEncoding, "gzip") !== false) {
            $compressed = gzencode($body);
            header("Content-Encoding: gzip");
            header("Vary: Accept-Encoding");
            header("Content-Length: " . strlen($compressed));
            echo $compressed;
        } else {
            echo $body;
        }
    }

    final protected function json( array $result ): string{
        $isJSONP = array_key_exists( "jsonpcallback", $this->getData("queries") );
        $jsonpCallback = ( $isJSONP ) ? $this->getData("queries.jsonpcallback") : null;
        $finalResult = $this->modifyResult($result);
        return Generator::generateJSON($finalResult, $jsonpCallback);
    }

    final protected function html(string $tpl, array $result = null): string{
        header("Content-type: text/html; charset=UTF-8");
        $finalResult = $this->modifyResult($result);
        $view = trim(Config::getConfig("view"),"/");
        $env = trim(Config::getConfig("env"),"/");
        $template = trim($tpl,"/");

        if (file_exists("$view/$template.php")){
            return Generator::generateHTML("$env/$view/$template.php", $finalResult);
        }else{
            throw new \Core\Error\ErrorWrapper("Template file doesn't exist",404);
        }
    }

    final protected function xml(array $result): string{
        $finalResult = $this->modifyResult($result);
        return Generator::generateXML($finalResult);
    }

    private function modifyResult(array $result): array{
        $this->setData("records", $result);
        $this->executeMiddlewares("after");
        return $this->getData("records");
    }
}

?>