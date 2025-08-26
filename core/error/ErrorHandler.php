<?php

namespace Core\Error;

use Core\Utils;
use Core\Config;
use Core\Data;
use Core\Generator;
use Core\traits\Log;

class ErrorHandler extends Data{
    use Log;
    public static function register(){
        set_exception_handler([self::class, "handleException"]);
        set_error_handler([self::class, "handleError"], E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
        register_shutdown_function([self::class, "handleShutdown"], E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool {
        if (!(error_reporting() & $errno)) return false;
        if (in_array($errno, [E_ERROR, E_USER_ERROR])){
            throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
        return true;
    }

    public static function handleShutdown(): void {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    public static function handleException(\Throwable $exception){
        $error = $exception;
        $code = $exception->getCode();
        if ($exception instanceof \Core\Error\ErrorWrapper){
            $error = $exception->getOriginal();
            $exception->getRawCode();
        }        
        $status = (Utils::responseHeader($exception->getCode()) != "") ? $exception->getCode() : 500;
        $showerror = ($error->getMessage() == "Environment is not recognized") ? true : Config::getConfig("showerror") ?? true;
        $logerror = Config::getConfig("logerror") ?? true;
        $errorformat = Config::getConfig("settings.errorformat","app") ?? "JSON";
        $message = $error->getMessage() ?? "Unknown error";

        http_response_code($status);
        
        $response = [
            "status"  => $status,
            "message" => Utils::responseHeader($status, true),
            "time" => date("Y-m-d H:i:s"),
            "result" => []
        ];

        $trace = explode("\n",preg_replace('/^#\d+\s*/m', '', $error->getTraceAsString()));
        $route = self::getData("urls.route");
        $detail = [            
            "path" => self::getData("urls.path"),
            "method" => self::getData("urls.method"),
            "route" => is_null($route) ? "N/A. The error didn't reach controller" : $route,            
            "code" => $code,
            "message" => $message,
            "file" => $error->getFile(),
            "line" => $error->getLine(),
            "trace" => $trace,
        ];
        $errNum = self::generateErrorNumber();
        $noshow = ["message" => "An error occurred on the server. Please refer to the log with the error number: #$errNum"];
        $response["result"] = ($showerror) ? $detail : $noshow;
        if ($logerror) self::logError(message: $detail, errornumber: "#$errNum\n");

        switch( $errorformat ){
            case "HTML":
                $env = trim(Config::getConfig("env"),"/");
                die(Generator::generateHTML("$env/core/error/error-view.php",$response));
                break;
            case "XML":
                die(Generator::generateXML($response));
                break;
            default:
                die(Generator::generateJSON($response));
                break;
        }
    }

    private static function generateErrorNumber($length = 8) {
        $time = microtime(true);
        $random = bin2hex(random_bytes(intval(ceil($length / 2))));
        $hash = md5($time . $random);
        return substr($hash, 0, $length);
    }
}
?>