<?php

namespace Core;

use Core\Traits\HandlesData;
use Core\Traits\Log;

abstract class Data {
    use HandlesData;
    use Log;

    private static array $data = [
        "data" => [],
        "headers" => [],
        "keys" => [],
        "queries" => [],
        "records" => [],
        "urls" => []
    ];

    private static array $lockedKeys = [];

    protected static function getData(string $key): mixed {
        return self::getDataStore($key, "data");
    }

    protected static function setData(string $key, mixed $value): void {
        if (self::isLocked($key) && !self::hasData($key)) return;

        $ref = &self::setDataStore($key, self::$data);
        $ref = $value;
    }

    protected static function hasData(string $key): bool {
        return self::hasDataStore($key, self::$data);
    }

    protected static function all(): array {
        return self::$data;
    }

    protected static function clearData(): void {
        self::$data = [];
    }

    protected static function lockData(string $key): void {
        if (!in_array($key, self::$lockedKeys)) {
            self::$lockedKeys[] = $key;
        }
    }

    protected static function isLocked(string $key): bool {
        return in_array($key, self::$lockedKeys);
    }

}

?>