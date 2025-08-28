<?php

namespace Core\Traits;

trait Log {
    protected static string $logDir = __DIR__ . "/../../log";

    protected static function write(string $level, mixed $message, array $context = [], string $errornumber = ""): void {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        $date = date("Y-m-d H:i:s");
        $fileDate = date("Y-m-d");
        $logFile = "snappy-{$fileDate}.log";

        $interpolated = self::interpolate($message, $context);
        $line = "[{$date}] {$level}: {$errornumber}" . $interpolated . PHP_EOL;
        file_put_contents(self::$logDir . "/" . $logFile, $line, FILE_APPEND);
    }

    protected static function interpolate(mixed $message, array $context = []): string {
        $msg = (is_array($message) || is_object($message)) ? print_r($message, true) : (string) $message;
        foreach ($context as $key => $value) {
            $msg = str_replace("{{$key}}", print_r($value, true), $msg);
        }
        return $msg;
    }

    public static function logInfo(mixed $message): void {
        self::write("INFO", $message);
    }

    public static function logDebug(mixed $message, array $context = []): void {
        self::write("DEBUG", $message, $context);
    }

    public static function logError(mixed $message, array $context = [], string $errornumber = ""): void {
        self::write("ERROR", $message, $context, $errornumber);
    }
}
