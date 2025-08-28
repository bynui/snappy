<?php

namespace Core;

use Core\Traits\HandlesData;
use Core\Traits\Log;

class Config {
    use HandlesData;
    use Log;
    private static array $data = [];
    private static array $lockedKeys = [];

    private static function load(string $configname): void {
        $file = __DIR__ . "/../config/{$configname}.php";
        if(!file_exists($file)) throw new \Core\Error\ErrorWrapper("Configuration file is not found");
        if (isset(self::$data[$configname])) return;
        self::$data[$configname] = include $file;
        self::$lockedKeys[$configname] = [];
    }

    public static function getConfig(string $key, string $configname = "environment"): mixed {
        self::load($configname);
        $data = self::$data[$configname];
        if ($configname === "environment") {
            $host = $_SERVER["HTTP_HOST"];
            $protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" || $_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://";
            $path = rtrim($protocol . $host . dirname($_SERVER["PHP_SELF"]), "/");
            $env = array_search($path, $data["env"] ?? []);
            if (!$env) throw new \Core\Error\ErrorWrapper("Environment is not recognized. Please check config/environment.php");
            $key = ($key === "env") ? "$key.$env" : "$env.$key";
        }
        
        return self::getDataStore($key, $configname) ?? [];
    }

    public static function hasConfig(string $key, string $configname = "environment"): bool {
        self::load($configname);
        return self::hasDataStore($key, self::$data[$configname]);
    }

    public static function allConfig(string $configname = "environment"): array {
        self::load($configname);
        return self::$data[$configname];
    }

}

?>