<?php

class CoreX_View_Helper_DebugBar extends Zend_View_Helper_Abstract {

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
     */
    public function __construct() {
        $this->_debugbar = CoreX_DebugBar::getInstance()->getStandardDebugBar();
        $this->_debugbarRenderer = CoreX_DebugBar::getInstance()->getJavascriptRenderer();
    }

    /**
     *
     * @return string
     */
    public function renderHead() {
        return $this->_debugbarRenderer->renderHead();
    }

    /**
     *
     * @return string
     */
    public function renderDebugBar() {
        return $this->_debugbarRenderer->render();
    }

}
