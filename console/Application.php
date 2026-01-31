<?php

namespace Console;

use Console\Generate;

class Application{

    private array $commands = [];

    public function __construct(){
        $this->register(new Generate());
    }

    private function register(Command $command){
        $this->commands[$command->name()] = $command;
    }

    public function run(array $argv){
        $command = $argv[1] ?? null;

        if (!$command) {
            echo "Snappy CLI\n";
            echo "php snappy generate controller:User\n";
            return;
        }

        if (!isset($this->commands[$command])) {
            echo "Command not found\n";
            return;
        }

        $this->commands[$command]->handle(array_slice($argv, 2));
    }
}

?>