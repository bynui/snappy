<?php

namespace Console;

abstract class Command{

    abstract public function name(): string;
    abstract public function handle(array $args): void;

    protected function info(string $msg){
        echo "[OK] {$msg}\n";
    }

    protected function error(string $msg){
        echo "[ERROR] {$msg}\n";
    }
}


?>