<?php


class Core_Controller_Plugin_Translations extends Zend_Controller_Plugin_Abstract{
    
    /**
     *
     * @var array
     */
    protected $_config = array();
    
    /**
     *
     * @var Zend_Cache_Core 
     */
    protected $_cache = null;
    
    /**
     *
     * @var Zend_Session_Namespace 
     */
    protected $_session = null;
    
    /**
     * 
     * @param array $translationsConfig
     * @param Zend_Cache_Core $cache
     */
    public function __construct($config = array(), $cache=null) {
        $this->_config = $config;
        $this->_cache = $cache;
        $this->_session = new Zend_Session_Namespace();
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        //double check if lang isset
        $lang = null;

        if(isset($this->_session->lang)){
            $lang = $this->_session->lang;
        }
        
        if(empty($lang)){
            $lang = $this->_getDefaultLang();
        }
        
        $translationsPath = $this->_getTranslationsBasePath();
        if($this->_isCacheEnabled()){
            Zend_Translate::setCache($this->_cache);            
        }
        
        if(is_dir($translationsPath.DS.$lang)){
            $translate = new Zend_Translate('array', $translationsPath.DS.$lang, $lang);
            if ($translate->isAvailable($lang)) {
                $translate->setLocale($lang);
            } else {                
                $translate->setLocale($this->_getDefaultLang());
            }    
            
            Zend_Registry::set('Zend_Translate', $translate);
        }
    }
    
    /**
     * 
     * @return bool
     */
    protected function _isCacheEnabled(){
        return (int)Core_Array::get($this->_config['cache'], 'enable',0) === 1 && $this->_cache instanceof Zend_Cache_Core;
    }
    
    /**
     * 
     * @return string
     */
    protected function _getTranslationsBasePath(){
        return (isset($this->_config['path']) && is_dir($this->_config['path']))?$this->_config['path']:(APPLICATION_PATH.DS."language");
    }
    
    /**
     * 
     * @return string
     */
    protected function _getDefaultLang(){
        $locale = Zend_Registry::get('Zend_Locale');     
        return Core_Array::get($this->_config, 'defaultLang', $locale->getLanguage());
    }
}
