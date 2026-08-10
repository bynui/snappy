<?php

namespace Core;

use Core\Utils;

class Generator{
    private const SEARCH = array("\r\n", "\n", "\r");
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

    public static function generateCSV( array $result, bool $hasHeader = false ): string{
        header("Content-Type: text/csv; charset=UTF-8");
        http_response_code($result["status"]);

        $records = $result["result"] ?? [];
        $stream = fopen("php://temp", "r+");
        // UTF-8 BOM improves compatibility with spreadsheet applications (e.g. Excel)
        fwrite($stream, "\xEF\xBB\xBF");
        $headerWritten = false;

        foreach ($records as $row) {
            if (is_array($row)) {
                if ($hasHeader && !$headerWritten) {
                    fputcsv($stream, array_keys($row));
                    $headerWritten = true;
                }
                fputcsv($stream, array_values(str_replace(self::SEARCH, "", $row)));
            }
        }

        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);
        return $csv;
    }

    public static function generateXML( array $result ): string{
        header("Content-type: application/xml;charset=UTF-8");
        http_response_code($result["status"]);
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
                // strip \r\n, \n, \r so they don't become &#13; / line breaks in the XML output
                $cleanValue = str_replace(self::SEARCH, "", (string)$value);
                if (is_numeric($key)) {
                    $subnode = $xmlData->addChild("record", Utils::validateXmlString($cleanValue));
                    $subnode->addAttribute("index", (string)$key);
                } else {
                    $xmlData->addChild($key, Utils::validateXmlString($cleanValue));
                }
            }
        }
    }
}

?>