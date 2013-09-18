<?php


class Core_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract{
    
    /**
     *
     * @var Zend_Locale
     */
    protected $defaultLocale = null;
    
    /**
     *
     * @var array
     */
    protected $_availableLanguages = array();
    
    /**
     *
     * @var string
     */
    protected $_defaultLang = null;
    
    /**
     *
     * @var array
     */
    protected $_languageDomains = array();

    /**
     *
     * @var Zend_Session_Namespace 
     */
    protected $_session = null;
    
    /**
     * 
     * @param Zend_Locale $locale
     * @param array $availableLanguages
     */
    public function __construct($defaultLocale, $availableLanguages, $languageDomains=array(),$defaultLang=null) {
        $this->_defaultLocale = $defaultLocale;
        $this->_availableLanguages = $availableLanguages;
        $this->_defaultLang = $defaultLang;
        $this->_languageDomains = $languageDomains;   
        $this->_session = new Zend_Session_Namespace();
    }
    
    public function routeStartup(\Zend_Controller_Request_Abstract $request) {  
        $locale = null;
        //domain  language check
        if(!empty($this->_languageDomains)){
            $currentDomain = Core_Uri_Helper::getBaseUrl();
            
            if(key_exists($currentDomain, $this->_languageDomains)){
                $domainLang = $this->_languageDomains[$currentDomain];
                if($this->_isAvailableLanguage($domainLang)){
                    $locale = new Zend_Locale($domainLang);
                }
            }
        }
        
        //route check
        if($request->getRequestUri() !== '/'){
            $parts = explode('/',trim(trim($request->getRequestUri(),'/')));
            
            if(isset($parts[0]) && $this->_isAvailableLanguage($parts[0])){
                $locale = new Zend_Locale($parts[0]);
            }
        }
        
        $lang = $this->_getRequestParam($request, 'lang', null);        
                
        if(!empty($lang)){            
            $this->_session->lang = $lang;
        }
        
        if(isset($this->_session->lang)){
            $lang = $this->_session->lang;
            $locale = new Zend_Locale($lang);
        }
        
        //setting default lang
        if($locale == null && $this->_defaultLang !== null && $this->_isAvailableLanguage($this->_defaultLang)){
            $locale = new Zend_Locale($this->_defaultLang);
        }
        
        if($locale == null){
            $locale = new Zend_Locale();
        }
                        
        //failsafe check, if failed go to error page
        if(!$this->_isAvailableLanguage($locale->getLanguage())){
            $request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('error');
        }
        
        Zend_Registry::set('Zend_Locale', $locale);
    }
    
    /**
     * 
     * @param string $lang
     * @return bool
     */
    protected function _isAvailableLanguage($lang)
    {
        return in_array($lang, $this->_availableLanguages);
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Http $request
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    protected function _getRequestParam(Zend_Controller_Request_Http $request, $param, $default=null)
    {
        return Core_Array::get($request->getQuery(), $param, $default);
    }
    
}
