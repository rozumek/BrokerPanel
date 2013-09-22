<?php

class Core_Json extends Zend_Json{
    
    /**
     * Decodes the given $encodedValue string which is
     * encoded in the JSON format
     *
     * Uses ext/json's json_decode if available.
     *
     * @param string $encodedValue Encoded in JSON format
     * @param int $objectDecodeType Optional; flag indicating how to decode
     * objects. See {@link Zend_Json_Decoder::decode()} for details.
     * @return mixed
     */
    public static function decode($encodedValue, $objectDecodeType = Zend_Json::TYPE_ARRAY) {
        $encodedValue = preg_replace("/(![\"][a-zA-Z0-9_]+?![\"]):/" , "\"$1\":", $encodedValue);

        return parent::decode(urldecode($encodedValue), $objectDecodeType);
    }
    
}
