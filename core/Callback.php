<?php

namespace Core;
use Core\traits\Log;

class Callback extends Data {

    final protected static function setData(string $key, mixed $value): void {
        throw new \Core\Error\ErrorWrapper("Method setData() is undefined in callback values. Use getData() instead.");
    }

    final protected static function clearData(): void {
        throw new \Core\Error\ErrorWrapper("Method clearData() is undefined in callback values. Use getData() instead.");
    }

    final protected static function lockData(string $key): void {
        throw new \Core\Error\ErrorWrapper("Method lockData() is undefined in callback values. Use getData() instead.");
    }

    final protected static function isLocked(string $key): bool {
        throw new \Core\Error\ErrorWrapper("Method isLocked() is undefined in callback values. Use getData() instead.");
    }
}

?>