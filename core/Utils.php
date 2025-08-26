<?php

namespace Core;
use Core\Config;
use Core\traits\Log;

class Utils{
    use Log;
    public static function responseHeader(int $code, bool $textonly = false): string{
        $http = include("config/responsecode.php");
        $protocol = (isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.0");
        if (!array_key_exists($code, $http)) return "";
        return ($textonly) ? $http[ $code ] : $protocol ." ". $code ." ".$http[ $code ];
    }

    public static function response( int $code, array $msg = [] ): string{
            header( self::responseHeader((int) $code) );
            header("Content-Type: application/json; charset=UTF-8");
            return json_encode(array(
                "status" => $code,
                "message" => self::responseHeader($code, true),
                "result" => $msg
            ));
        }   

    public static function constructSchema(array $data, array &$result = []): array{
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::constructSchema($value, $result);
            } else {
                $result[":$key"] = self::validateString($value);
            }
        }
        return $result;
    }

    public static function validateString(string $str): string{
        return stripslashes(strip_tags(str_replace("'","",$str)));
    }

    public static function validateXmlString(string $str): string {
        $str = mb_convert_encoding($str, "UTF-8", "UTF-8");

        if ($str !== strip_tags($str)) {
            return "<![CDATA[" . str_replace("]]>", "]]]]><![CDATA[>", $str) . "]]>";
        }

        return htmlspecialchars($str, ENT_QUOTES | ENT_XML1, "UTF-8");
    }

   
    public static function getHeaders(): array {
        $headers = array_merge(getallheaders(), $_SERVER);
        $allowedHeader = Config::getConfig("headers","app");
        $keys = array_keys(array_filter($headers, function($value, $key) use ($allowedHeader) {
            return count(array_intersect($allowedHeader, explode("_", $key))) > 0;
        }, ARRAY_FILTER_USE_BOTH));
        $headers = array_intersect_key($headers, array_flip($keys));

        return $headers;
    }
            
    public static function testQuery(string $sql, array $params = []): void{
        foreach($params as $key => $value) $sql = str_replace($key, "'$value'", $sql);
        Log::logDebug($sql);
    }
}
?>
