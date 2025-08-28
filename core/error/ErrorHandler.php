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
        set_error_handler([self::class, "handleError"]);
        register_shutdown_function([self::class, "handleShutdown"]);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        if (in_array($errno, [E_WARNING, E_USER_WARNING], true)) {
            return true;
        }

        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);

    }

    public static function handleShutdown(): void {
        $error = error_get_last();
        if ($error && in_array($error["type"], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            self::handleError($error["type"], $error["message"], $error["file"], $error["line"]);
        }
    }

    public static function handleException(\Throwable $exception){
        $error = $exception;
        $code = $exception->getCode();
        if ($exception instanceof \Core\Error\ErrorWrapper){
            $error = $exception->getOriginal();
            $code = $exception->getRawCode();
        }        
        $status = (Utils::responseHeader($exception->getCode()) != "") ? $exception->getCode() : 500;
        $message = $error->getMessage() ?? "Unknown error";

        try{
            $param = [
                "error" => $error,
                "message" => $message,
                "status" => $status,
                "code" => $code,
                "showerror" => Config::getConfig("showerror"),
                "logerror" => Config::getConfig("logerror") ?? true,
                "errorformat" => Config::getConfig("settings.errorformat","app") ?? "JSON",
                "env" => trim(Config::getConfig("env"),"/")
            ];
            self::generateError($param);
            
        }catch(\Exception $e){
            $param = [
                "error" => $error,
                "message" => $message,
                "status" => $status,
                "code" => $code,
                "showerror" => false,
                "logerror" => true,
                "errorformat" => "JSON"                
            ];
            self::generateError($param);
        }
    }

    private static function generateError(array $param): void{
        http_response_code($param["status"]);
        
        $response = [
            "status"  => $param["status"],
            "message" => Utils::responseHeader($param["status"], true),
            "time" => date("Y-m-d H:i:s"),
            "result" => []
        ];

        $tracelist = ($param["errorformat"] != "HTML") ? $param["error"]->getTraceAsString() : preg_replace("/^#\d+\s*/m", "", $param["error"]->getTraceAsString());
        $trace = explode("\n",$tracelist);
        $route = self::getData("urls.route");
        $detail = [            
            "path" => self::getData("urls.path"),
            "method" => self::getData("urls.method"),
            "route" => is_null($route) ? "N/A. The request didn't reach your controller" : $route,
            "code" => $param["code"],
            "message" => $param["message"],
            "file" => $param["error"]->getFile(),
            "line" => $param["error"]->getLine(),
            "trace" => $trace,
        ];
        $errNum = self::generateErrorNumber();
        $noshow = ["message" => "An error occurred on the server. Please refer to the log with the error number: #$errNum"];
        $response["result"] = ($param["showerror"]) ? $detail : $noshow;
        if ($param["logerror"]) self::logError(message: $detail, errornumber: "#$errNum\n");

        switch( $param["errorformat"] ){
            case "HTML":
                die(Generator::generateHTML($param["env"]."/core/error/error-view.php",$response));
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