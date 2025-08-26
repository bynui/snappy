<?php

namespace Core;

abstract class Middleware extends Data{

    private static $globalMiddlewares = [];

    final protected function executeMiddlewares(string $sequence): void {
        $callerClass = get_class($this);
        if (!str_contains($callerClass,"Controller\\")){
            return;
        }
        if (!count(self::$globalMiddlewares)) {
            self::$globalMiddlewares = include ("config/middleware.php");
        }
    
        $controllerMiddleware = $this::getData("urls.controller");
        $className = "Middleware\\".ucfirst(trim($controllerMiddleware));
        if (!in_array($controllerMiddleware, self::$globalMiddlewares) && class_exists($className)) {
            array_push(self::$globalMiddlewares, $controllerMiddleware);
        }
    
        $middlewareOrder = ($sequence == "before") ? self::$globalMiddlewares : array_reverse(self::$globalMiddlewares);
        foreach ($middlewareOrder as $value) {
            $class = "Middleware\\$value";
            $middlewareClassName = new $class;
            $method = strtolower($this::getData("urls.method"));
    
            if (method_exists($middlewareClassName, $method)) {
                call_user_func([$middlewareClassName, $method], $sequence);
            }
        }
    }
}

?>