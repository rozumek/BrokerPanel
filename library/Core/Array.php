<?php

class Core_Array extends ArrayIterator {

    /**
     * 
     * @param array $array
     * @param key $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = null) {
        if (isset($array)) {
            if (key_exists($key, $array) && $array[$key] !== null && $array[$key] !== '') {
                return $array[$key];
            }
        }

        return $default;
    }

    /**
     * 
     * @param Traversable|array $value
     * @return boolean
     */
    public static function isArray($value) {
        if (is_array($value)) {
            return true;
        } else {
            return (is_object($value) AND $value instanceof Traversable);
        }

        return false;
    }

    /**
     * Is the array associative or not?
     *
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array) {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /**
     * array_key_exists for checking keys argument that is array of keys
     * 
     * @param array|string|int $needle
     * @param array $haystack
     * @return boolean
     */
    public static function keyExists($needle, array $haystack) {
        if (is_array($needle)) {
            foreach ($needle as $key) {
                if (!array_key_exists($key, $haystack)) {
                    return false;
                }
            }

            return true;
        } else {
            return array_key_exists($needle, $haystack);
        }
    }

    /**
     * 
     * @param array $array
     * @param mixed $default
     * @return mixed
     */
    public static function first(array $array, $default = null) {
        if (count($array) > 0) {
            reset($array);
            return current($array);
        }

        return $default;
    }

    /**
     * 
     * @param array $array
     * @param mixed $default
     * @return mixed
     */
    public static function last(array $array, $default = null) {
        if (count($array) > 0) {
            return end($array);
        }

        return $default;
    }

    /**
     * 
     * @param array $array
     * @return type
     */
    public static function flatten(array $array) {
        $flatArray = array();

        foreach ($array as $key => $element) {
            if (is_string($element)) {
                $flatArray[$key] = $element;
            } else if (is_array($element) || is_object($element)) {
                $flatArray = array_merge(
                    $flatArray, 
                    (array) $element
                );
            }
        }

        return $flatArray;
    }

    /**
     * Parses array to SimpleXMLElement object and returns it as xml string output
     * 
     * @param array $array
     * @param SimpleXMLElement $xml
     * @param string $root
     * @return string
     */
    public static function toXml(array $array, $xml = false, $root = '<root/>') {
        if ($xml === false) {
            $xml = new SimpleXMLElement($root);
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                self::toXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }

    /**
     * 
     * @param array $array
     * @return null
     */
    public static function toCsv(array $array) {
        if (count($array) == 0) {
            return null;
        }

        ob_start();
        $stream = fopen("php://output", 'w');
        fputcsv($stream, array_keys(reset($array)));

        foreach ($array as $row) {
            fputcsv($stream, $row);
        }

        fclose($stream);

        return ob_get_clean();
    }

}