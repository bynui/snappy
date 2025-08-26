<?php

namespace Core;

use Core\Traits\HandlesData;
use Core\Traits\Log;

class Config {
    use HandlesData;
    use Log;
    private static array $data = [];
    private static array $lockedKeys = [];

    private static function load(string $flag): void {
        $file = __DIR__ . "/../config/{$flag}.php";
        if(!file_exists($file)) throw new \Core\Error\ErrorWrapper("Configuration file is not found");
        if (isset(self::$data[$flag])) return;
        self::$data[$flag] = include $file;
        self::$lockedKeys[$flag] = [];
    }

    public static function getConfig(string $key, string $flag = "environment"): mixed {
        self::load($flag);
        $data = self::$data[$flag];
        if ($flag === "environment") {
            $host = $_SERVER["HTTP_HOST"];
            $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" || $_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://";
            $path = rtrim($protocol . $host . dirname($_SERVER["PHP_SELF"]), "/");
            $env = array_search($path, $data["env"] ?? []) ?: "development";
            if ($env == "error") throw new \Core\Error\ErrorWrapper("Environment is not recognized");
            $key = ($key === "env") ? "$key.$env" : "$env.$key";
        }
        
        return self::getDataStore($key, $flag) ?? [];
    }

    public static function hasConfig(string $key, string $flag = "environment"): bool {
        self::load($flag);
        return self::hasDataStore($key, self::$data[$flag]);
    }

    public static function allConfig(string $flag = "environment"): array {
        self::load($flag);
        return self::$data[$flag];
    }

}

?>