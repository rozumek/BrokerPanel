<?php

/**
 * Description of Log
 *
 * @author Marcinek
 */
class Core_Resource_Log extends Zend_Application_Resource_ResourceAbstract{
    
    /**
     *
     * @var Zend_Log 
     */
    protected $_logger = null;
    
    /**
     *
     * @var string 
     */
    protected $_separator = ';';
    
    /**
     * 
     * @return Zend_Log
     */
    public function init() 
    {      
        if($this->_isEnabled()){
            if($this->_logger == null){            
                $loggerConfig = array(
                    'timestampFormat' => 'Y-m-d H:i:s'
                );
                
                if($this->_getLogLevel() & 1){
                    $loggerConfig['errorLog'] = array(
                        'writerName' => 'Stream',
                        'writerParams' => array(
                            'stream' => APPLICATION_PATH."/../data/logs/error.log",
                            'mode' => 'a'
                        ),
                        'formatterName' => 'Simple',
                        'formatterParams' => array(
                            'format' => "%timestamp%{$this->_separator}%priorityName%{$this->_separator}%priority%{$this->_separator}%message%{$this->_separator}%info%".PHP_EOL,
                        ),
                        'filterName' => 'Priority',
                        'filterParams' => array(
                            'priority' => Zend_Log::NOTICE,
                            'operator' => "<="
                        ),
                    );
                }

                if($this->_getLogLevel() & 2){
                    $loggerConfig['debugLog'] = array(
                        'writerName' => 'Stream',
                        'writerParams' => array(
                            'stream' => APPLICATION_PATH."/../data/logs/debug.log",
                            'mode' => 'a'
                        ),
                        'formatterName' => 'Simple',
                        'formatterParams' => array(
                            'format' => "%timestamp%{$this->_separator}%message%{$this->_separator}%info%".PHP_EOL,
                        ),
                        'filterName' => 'Priority',
                        'filterParams' => array(
                            'priority' => Zend_Log::DEBUG,
                            'operator' => "="
                        ),
                    );
                }

                if($this->_getLogLevel() & 4){
                    $loggerConfig['accessLog'] = array(
                        'writerName' => 'Stream',
                        'writerParams' => array(
                            'stream' => APPLICATION_PATH."/../data/logs/access.log",
                            'mode' => 'a'
                        ),
                        'formatterName' => 'Simple',
                        'formatterParams' => array(
                            'format' => "%timestamp%{$this->_separator}%user%{$this->_separator}%message%{$this->_separator}%info%".PHP_EOL,
                        ),
                        'filterName' => 'Priority',
                        'filterParams' => array(
                            'priority' => Core_Log::ACCESS,
                            'operator' => "="
                        ),
                    );
                }

                if($this->_getLogLevel() & 8){
                    $loggerConfig['transactionLog'] = array(
                        'writerName' => 'Stream',
                        'writerParams' => array(
                            'stream' => APPLICATION_PATH."/../data/logs/transact.log",
                            'mode' => 'a'
                        ),
                        'formatterName' => 'Simple',
                        'formatterParams' => array(
                            'format' => "%timestamp%{$this->_separator}%message%{$this->_separator}%info%".PHP_EOL,
                        ),
                        'filterName' => 'Priority',
                        'filterParams' => array(
                            'priority' => Core_Log::TRANSACT,
                            'operator' => "="
                        ),
                    );  
                }
                            
                $this->_logger = Core_Log::factory($loggerConfig)                        
                        ->addPriority('ACCESS', 8)
                        ->addPriority('TRANSACT', 9)
                        ->registerErrorHandler()
                    ;
            }           
        }else{            
            $writer = new Zend_Log_Writer_Null();
            $this->_logger = new Core_Log($writer);        
        }
        
        return $this->_logger;
        
    }
    
    /**
     * 
     * @return bool
     */
    private function _isEnabled()
    {
        $options = $this->getOptions();        
        return intval($options['enable']) === 1;
    }
    
    /**
     * 
     * @return int
     */
    private function _getLogLevel()
    {
        $options = $this->getOptions();
        return bindec($options['level']);
    }

}
