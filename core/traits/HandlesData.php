<?php

namespace Core\Traits;

trait HandlesData {
    protected static function getDataStore(string $key, string $flag = "default"): mixed {        
        $data = ($flag == "data") ? self::$data : self::$data[$flag];
        foreach (explode(".", $key) as $segment) {
            if (!isset($data[$segment])) return null;
            $data = $data[$segment];
        }

        return $data;
    }
    
    protected static function &setDataStore(string $key, array &$data): mixed {
        $ref = &$data;
        foreach (explode(".", $key) as $segment) {
            if (!isset($ref[$segment]) || !is_array($ref[$segment])) {
                $ref[$segment] = [];
            }
            $ref = &$ref[$segment];
        }
        return $ref;
    }

    protected static function hasDataStore(string $key, array $data): bool {
        foreach (explode(".", $key) as $segment) {
            if (!array_key_exists($segment, $data)) return false;
            $data = $data[$segment];
        }
        return true;
    }
}

?>