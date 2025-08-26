<?php

use Core\Index;
use Core\Error\ErrorHandler;

require __DIR__ . "/vendor/autoload.php";

ErrorHandler::register();

$index = new Index();
$index->run();
?>