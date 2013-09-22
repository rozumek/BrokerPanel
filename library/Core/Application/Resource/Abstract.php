<?php

abstract class Core_Application_Resource_Abstract extends Zend_Application_Resource_ResourceAbstract {

    /**
     *
     * @param string $name
     * @return Zend_Cache_Core
     */
    public function getCacheManager($name) {
        return $this->getBootstrap()
                ->bootstrap('cachemanager')
                ->getResource('cachemanager')
                ->getCache($name);
    }

}
