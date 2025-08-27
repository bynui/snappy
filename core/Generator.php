<?php

namespace Core;

use Core\Utils;

class Generator{

    public static function generateJSON( array $result, string $jsonpCallback = null ): string{
        $isJSONP = !is_null($jsonpCallback) ? true : false;
        $contentType = ( !$isJSONP ) ? "application/json" : "application/javascript";
        header("Content-Type: $contentType; charset=UTF-8");
        http_response_code($result["status"]);
        return ( !$isJSONP ) ? json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $jsonpCallback."(".json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).")";
    }

    public static function generateHTML(string $tpl, array $result = null): string{
        header("Content-type: text/html; charset=UTF-8");
        $postdata = (!is_null($result)) ? http_build_query($result) : "";
        $ch = curl_init($tpl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public static function generateXML( array $result ): string{
        header("Content-type: application/xml;charset=UTF-8");
        http_response_code($finalResult["status"]);
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><root/>');
        self::parseXML( $result, $xml);
        return $xml->asXML();			
    }

    private static function parseXML(array $data, \SimpleXMLElement &$xmlData): void {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $subnode = $xmlData->addChild("record");
                    $subnode->addAttribute("index", (string)$key);
                    self::parseXML($value, $subnode);
                } else {
                    $subnode = $xmlData->addChild($key);
                    self::parseXML($value, $subnode);
                }
            } else {
                if (is_numeric($key)) {
                    $subnode = $xmlData->addChild("record", Utils::validateXmlString((string)$value));
                    $subnode->addAttribute("index", (string)$key);
                } else {
                    $xmlData->addChild($key, Utils::validateXmlString((string)$value));
                }
            }
        }
    }
}

?>