<?php

class Core_Controller_Plugin_SystemMessages extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var string
     */
    protected $_messagesLayout = 'system-mssages.phtml';
        
    /**
     *
     * @var string
     */
    protected $_messagesScriptPath = '';
    
    /**
     *
     * @var Zend_View 
     */
    protected $_view = null;
    
    /**
     * 
     * @param string $messagesLayout
     */
    public function __construct(Zend_View $view, $messagesScriptPath, $messagesLayout='system-mssages.phtml') {
        $this->_view = $view;
        $this->_messagesScriptPath = $messagesScriptPath;
        $this->_messagesLayout = $messagesLayout;
    }
    
    /**
     * 
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(\Zend_Controller_Request_Abstract $request) {
        $messageQueue = Core_Application_MessagesQueue::getInstance();
        
        if(Zend_Registry::offsetExists('Zend_Translate') && ($translator = Zend_Registry::get('Zend_Translate'))instanceof Zend_Translate){
            $messageQueue->setTranslator($translator);
        }
        
        $messages = $messageQueue->getMessagesQueue();
        
        //rendering message queue
        if(!empty($messages)){
            //flushing messages queue
            $messageQueue->flushMessagesQueue();
            $messagesOutput = $this->_getView()
                    ->setScriptPath($this->_messagesScriptPath)
                    ->assign('messages', $messages)
                    ->render($this->_messagesLayout);
            
            if(!empty($messagesOutput)){
                //render output
                $this->_getView()
                        ->getHelper('Placeholder')
                        ->placeholder('system-messages')
                        ->set($messagesOutput);
            }
        }
    }

    /**
     * 
     * @return string
     */
    protected function _getLocaleLang(){
        $locale = new Zend_Locale;        
        $lang = $locale->getLanguage();
        return  ($lang)?$lang:'pl';
    }
    
    /**
     * 
     * @return Zend_View
     */
    protected function _getView(){
        return $this->_view;
    }        
    
}