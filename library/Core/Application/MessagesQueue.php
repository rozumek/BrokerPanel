<?php

class Core_Application_MessagesQueue extends Zend_Session_Namespace{
   
    /**
     *
     * @var string
     */
    protected $_namespace = 'SystemMessages';
    
    /**
     *
     * @var string
     */
    protected $_messagesKey = 'messages';
    
    /**
     *
     * @var Zend_Translate 
     */
    protected $_translatorAdapter = null;
           
    /**
     *
     * @var Core_Application_MessagesQueue 
     */
    protected static $_instance = null;
    
    /**
     *
     * @var bool
     */
    protected static $_initialized = false;
    
    /**
     * 
     */    
    public function __construct() {
        if(self::$_initialized === true){
            throw new Core_Application_MessagesQueue_CannotInstantializeException;
        }
        
        parent::__construct($this->_namespace, true);        
        self::$_instance = $this;
    }
    
    /**
     * 
     */
    public static function init(){
        if(self::$_instance === null){    
            self::$_instance = new Core_Application_MessagesQueue();
            self::$_initialized = true;
        }
    }


    /**
     * 
     * @return Core_Application_MessagesQueue
     */
    public function getInstance(){
        if(self::$_instance === null){
            throw new Core_Application_MessagesQueue_NotInitalizedException;
        }
        return self::$_instance;
    }


    /**
     * 
     * @return Zend_Translate
     */
    public function getTranslator(){
        return $this->_translatorAdapter;
    }
    
    /**
     * 
     * @param Zend_Translate $adapter
     * @return \Core_Application_MessagesQueue
     */
    public function setTranslator(Zend_Translate $adapter){
        $this->_translatorAdapter = $adapter;
        return $this;
    }

    /**
     * 
     * @param string $message
     * @param int $type
     * @return \Core_Application_MessagesQueue
     */
    public function addMessageToQueue($message, $type=Core_Log::INFO){
        $this->{$this->_messagesKey}[$type][] = $message;
        return $this;
    }
    
    /**
     * 
     * @param int $type
     * @return array
     */
    public function getMessagesQueue($type=null, $merged=false){
        if(isset($this->{$this->_messagesKey})){
            if($type !== null){
                if(isset($this->{$this->_messagesKey}[$type])){
                    return $this->_translateMessages($this->{$this->_messagesKey}[$type]);
                }
            }else{
                if($merged === true){//get all messages merged                 
                    $allTypeMessages = array();                
                    foreach($this->{$this->_messagesKey} as $type => $messages){
                        if(is_array($messages)){
                            $messages = $this->_translateMessages($messages);
                            $allTypeMessages = array_merge($allTypeMessages, $messages);
                        }
                    }

                    return $allTypeMessages;
                }else{
                    foreach($this->{$this->_messagesKey} as $type => &$messages){
                        $messages = $this->_translateMessages($messages);
                    }
                    return $this->{$this->_messagesKey};
                }
                
            }            
        }
        
        return array();
    }

    /**
     * 
     * @return \Core_Application_MessagesQueue
     */
    public function flushMessagesQueue(){
        $this->{$this->_messagesKey} = array();
        return $this;
    }
    
    /**
     * 
     * @param array $messages
     * @return array
     */
    protected function _translateMessages(&$messages){        
        if($this->getTranslator() !== null){
            foreach($messages as &$message){
                $message = $this->getTranslator()
                        ->getAdapter()
                        ->translate($message);
            }
        }
        
        return $messages;
    }
    
}
