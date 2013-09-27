<?php

require_once APPLICATION_PATH . '/../library/CoreX/DebugBar/autoload.php';

use DebugBar\StandardDebugBar;

class CoreX_DebugBar extends Core_Object_Abstract{

    /**
     *
     * @var bool
     */
    protected static $_singleInstance = true;

    /**
     *
     * @var StandardDebugBar
     */
    protected $_debugBar = null;

    /**
     *
     * @var JavascriptRenderer
     */
    protected $_debugbarRenderer = null;

    /**
     *
     * @var bool
     */
    protected static $_enabled = false;


    /**
     *
     */
    public function __construct() {
        $this->_debugBar = new StandardDebugBar();
        self::setInstance($this);
    }

    /**
     *
     * @return CoreX_DebugBar
     */
    public static function getInstance() {
        if (static::$_instance === null) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    /**
     *
     * @param bool $flag
     */
    public static function setEnabled($flag) {
        static::$_enabled = (bool) $flag;
    }

    /**
     *
     * @return bool
     */
    public static function isEnabled() {
        return static::$_enabled === true;
    }

    /**
     * @param mixed $arg
     */
    public static function dump() {
        if(func_num_args() > 0) {
            $args = func_get_args();

            foreach($args as $arg) {
                self::getInstance()->addMessage($arg);
            }
        }
    }

    /**
     *
     * @return StandardDebugBar
     */
    public function  getStandardDebugBar() {
        return $this->_debugBar;
    }

    /**
     *
     * @return JavascriptRenderer
     */
    public function getJavascriptRenderer() {
        return $this->_debugBar = $this->_debugBar->getJavascriptRenderer();
    }

    /**
     *
     * @param mixed $message
     * @return \CoreX_DebugBar
     */
    public function addMessage($message) {
        $this->_debugBar['messages']->addMessage($message);
        return $this;
    }
}