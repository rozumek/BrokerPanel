<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initResources() {
        $this->bootstrap('frontController')
                ->bootstrap('db')
                ->bootstrap('view');

        //init system messages queue
        Core_Application_MessagesQueue::init();

        //create mandatory directories
        Core_File::mkdir(array(
            DATA_DIR,
            LOGS_DIR,
            CACHE_DIR,
            TMP_DIR,
        ));

        // set application version
        $versionFile  = APPLICATION_PATH . '/../version.txt';

        if(file_exists($versionFile)) {
            Zend_Registry::set('version', file_get_contents($versionFile));
        }
    }

    protected function _initConfig() {
        $this->bootstrap('appconfig');
    }

    protected function _initLanguage() {
        $this->bootstrap('locale')
                ->bootstrap('db')
                ->bootstrap('frontController');

        $locale = $this->getResource('locale');
        $frontController = $this->getResource('frontController');

        if ($this->_isMultiLanguageEnabled()) {
            $languagesModel = new Application_Model_Languages();
            $domainsModel = new Application_Model_Domains();
            $frontController->registerPlugin(new Core_Controller_Plugin_Language(
                    $locale, $languagesModel->getLanguagesShortCodes(), $domainsModel->getLanguageDomainMap(), $languagesModel->getDefaultLanguageShortCode()
            ));
        } else {
            Zend_Registry::set('Zend_Locale', $locale);
        }
    }

    protected function _initTranslations() {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $translationsConfig = $this->_getAppConfig('translations')->toArray();

        $translationsCacheEnable = (int) Core_Array::get($translationsConfig['cache'], 'enable', 0);
        $translationsCacheManagerName = Core_Array::get($translationsConfig['cache'], 'manager', 'translations');
        $translationsCache = ($translationsCacheEnable) ? $this->_getCacheManager($translationsCacheManagerName) : null;

        $frontController->registerPlugin(new Core_Controller_Plugin_Translations($translationsConfig, $translationsCache));
    }

    protected function _initRoutes() {
        $routerConfig = $this->_getAppConfig('router')
                ->toArray();

        $routerCacheEnable = (int) Core_Array::get($routerConfig['cache'], 'enable', 0);
        $routerCacheManagerName = Core_Array::get($routerConfig['cache'], 'manager', 'routers');
        $routerCache = ($routerCacheEnable) ? $this->_getCacheManager($routerCacheManagerName) : null;

        $this->getResource('frontController')
                ->registerPlugin(new Core_Controller_Plugin_Routes(
                        $routerConfig, $routerCache
        ));
    }

    protected function _initRoles() {
        $aclConfig = $this->_getAppConfig('acl')
                ->toArray();

        Application_Model_Roles::setGuestRoleId((int) Core_Array::get($aclConfig, 'guestRole', 1));
        Application_Model_Roles::setSuperRoleId((int) Core_Array::get($aclConfig, 'superRole', 2));
        Application_Model_Roles::setDefaultUserRole((int) Core_Array::get($aclConfig, 'defaultRole', 3));
    }

    protected function _initAcl() {
        $aclConfig = $this->_getAppConfig('acl')
                ->toArray();

        $guestRole = (int) Core_Array::get($aclConfig, 'guestRole', 1);
        $superRole = (int) Core_Array::get($aclConfig, 'superRole', 2);

        $enableAclCaching = (int) Core_Array::get($aclConfig['cache'], 'enable', 0);
        $aclCacheManagerName = Core_Array::get($aclConfig['cache'], 'manager', 'acl');
        $aclCacheId = Core_Array::get($aclConfig['cache'], 'id', 'acl');

        if ($enableAclCaching) {
            $aclCache = $this->_getCacheManager($aclCacheManagerName);
        }

        if (!$enableAclCaching || ($acl = $aclCache->load($aclCacheId)) === false) {
            $acl = Core_Acl::getInstance()
                    ->setDefaultRole($guestRole) // guest id from database
                    ->setSuperRole($superRole)   // administrator id from database
                    ->setResourcesProcessor(new Application_Model_Resources())
                    ->setRolesProcessor(new Application_Model_Roles())
                    ->setAclRulesProcessor(new Application_Model_Acl())
                    ->digest();
            if ($enableAclCaching) {
                $aclCache->save($acl);
            }
        } else {
            if (!Core_Acl::issetInstance()) {
                Core_Acl::setInstance($acl);
            }
        }


        $this->getResource('frontController')
                ->registerPlugin(new Core_Controller_Plugin_Acl());
    }

    protected function _initLogger() {
        $this->bootstrap('log');
        $logger = $this->getResource('log');

        if (!($logger instanceof Zend_Log)) {
            $logger = new Core_Log(new Zend_Log_Writer_Null());
        }

        Zend_Registry::set('log', $logger);

        //access log
        $this->getResource('frontController')
                ->registerPlugin(
                        new Core_Controller_Plugin_AccessLog(new Application_Model_Auth(Zend_Auth::getInstance()))
                );
    }

    protected function _initErrorHandler() {
        $this->bootstrap('frontController');
        $plugin = new Zend_Controller_Plugin_ErrorHandler(array(
            'module' => 'default',
            'controller' => 'error',
            'action' => 'error'
        ));

        $this->getResource('frontController')
                ->registerPlugin($plugin);

        return $plugin;
    }

    protected function _initMailer() {
        $this->bootstrap('mail');
        $mailer = $this->getResource('mail');

        if ($mailer instanceof Zend_Mail_Transport_Abstract) {
            Zend_Mail::setDefaultTransport($mailer);
        }
    }

    protected function _initSystemMessages() {
        $this->bootstrap('frontController')
                ->bootstrap('view');

        $view = $this->getResource('view');
        $systemMessagesConfig = $this->_getAppConfig('systemMessages');

        $this->getResource('frontController')
                ->registerPlugin(new Core_Controller_Plugin_SystemMessages(
                        $view, $systemMessagesConfig->scriptPath, $systemMessagesConfig->layout
                        )
        );
    }

    protected function _initMenu() {
        $menu = new Zend_Config_Xml(NAVIGATION_XML, 'navigation');
        $this->bootstrap('view');
        $this->getResource('view')
                ->navigation(new Zend_Navigation($menu)) //ustawienie menu głównego
                ->breadcrumbs()
                ->setRenderInvisible(true)  //renderowanie ukrytych pozycji
                ->setMinDepth(0);   // poziom zagnieżdzenia
    }

    /**
     *
     * @param string $name
     * @return Zend_Cache_Core
     */
    protected function _getCacheManager($name) {
        return $this->bootstrap('cachemanager')
                        ->getResource('cachemanager')
                        ->getCache($name);
    }

    /**
     *
     * @param string $key
     * @return Zend_Config_Ini
     */
    protected function _getAppConfig($key = null) {
        $appConfig = $this->bootstrap('appconfig')
                ->getResource('appconfig');

        if ($key !== null && isset($appConfig->{$key})) {
            return $appConfig->{$key};
        }

        return $appConfig;
    }

    /**
     *
     * @return boolean
     */
    protected function _isMultiLanguageEnabled() {
        $multilangConfig = $this->_getAppConfig('multilangual');
        if (isset($multilangConfig->enable) && (int) $multilangConfig->enable === 1) {
            return true;
        }

        return false;
    }

}

