<?php

/**
 * @method Zend_Controller_Request_Http getRequest()
 * @method Zend_Controller_Response_Http getResponse()
 */
class Core_Controller_Plugin_AccessLog extends Core_Log_Plugin_AccessLog_Abstract {

    /**
     *
     * @var array
     */
    protected $_filterData = array(
        'password'
    );

    /**
     *
     * @return string|int
     */
    protected function _getUserOrGuest() {
        if ($this->_auth->hasIdentity()) {
            return (int) $this->_auth->getIdentity()->getId();
        }

        return 'guest';
    }

    /**
     *
     * @return array
     */
    protected function _getData() {
        try {
            $data = $this->getRequest()->getRawBody();
            $json = Core_Json::decode($data);

            foreach ((array) $json as $name => $value) {
                if (in_array($name, $this->_filterData)) {
                    unset($json[$name]);
                }
            }

            $data = Core_Json::encode($json);

            return $data;
        } catch (Zend_Exception $e) {
            return array();
        }
    }

}