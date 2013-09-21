<?php

class Core_Dictionary {

    /**
     *
     * @var string
     */
    private static $_dictionaryNameFormat = '%s_Dictionary_%s';

    /**
     *
     * @param string $dictionary
     * @param string $namespace
     * @params $_
     * @return Core_Dictionary_Abstract
     * @throws Core_ClassNotFoundException
     */
    public static function getDictionary($dictionary, $namespace='Core') {
        $args = array();

        if(func_num_args() > 2) {
            $args = func_get_args();
            array_shift($args[0]);
            array_shift($args[1]);
        }

        $dict = self::_getDictionary($dictionary, $namespace, $args);

        return $dict;
    }

    /**
     *
     * @param string $dictionary
     * @param string $namespace
     * @params $_
     * @return array
     */
    public static function getDictionaryList($dictionary, $namespace='Core') {
        $args = array();

        if(func_num_args() > 2) {
            $args = func_get_args();
            array_shift($args[0]);
            array_shift($args[1]);
        }

        $dict = self::_getDictionary($dictionary, $namespace, $args);

        if($dict instanceof Core_Dictionary_Abstract) {
            return $dict->getList();
        }

        return array();
    }
    /**
     *
     * @param string $dictionary
     * @param string $namespace
     * @params $_
     * @return array
     */
    public static function getDictionaryCodes($dictionary, $namespace='Core') {
        $args = array();

        if(func_num_args() > 2) {
            $args = func_get_args();
            array_shift($args[0]);
            array_shift($args[1]);
        }

        $dict = self::_getDictionary($dictionary, $namespace, $args);

        if($dict instanceof Core_Dictionary_Abstract) {
            return $dict->getCodes();
        }

        return array();
    }

    /**
     *
     * @param string $dictionary
     * @param string $namespace
     * @param array $arguments
     * @return Core_Dictionary_Abstract
     * @throws Core_ClassNotFoundException
     */
    private static function _getDictionary($dictionary, $namespace, $arguments=array()) {
        $className = sprintf(self::$_dictionaryNameFormat, $namespace, $dictionary);

        if(!class_exists($className)) {
            throw new Core_ClassNotFoundException;
        }

        $refl = new ReflectionClass($className);

        /**
         * Issue: https://github.com/dczepierga/MenuMaker/issues/25
         *
         * RETURN ERROR: exception 'ReflectionException' with message
         * 'Class does not have a constructor, so you cannot pass any constructor arguments'
         *
         * @update: Fix is for method newInstanceWithoutConstructor that exists in PHP 5.4
         */
        if (method_exists($className, '__construct')) {
            $instance = $refl->newInstanceArgs($arguments);
        } else {
            if(method_exists($className, 'newInstanceWithoutConstructor')) {
                $instance = $refl->newInstanceWithoutConstructor();
            } else {
                $instance = $refl->newInstanceArgs();
            }
        }

        return $instance;
    }
}
