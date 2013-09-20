<?php

class Aktiv_Form_Element_CustomerAutocomplete extends ZendX_JQuery_Form_Element_AutoComplete {

    /**
     *
     * @var array
     */
    protected $_data = array();

    /**
     *
     * @return array
     */
    public function getAutocompleteData() {
        if (empty($this->_data)) {
            $clientsModel = new Application_Model_Clients();
            $this->_data = $clientsModel->getClientsList('name ASC', null, 0, (bool) $this->getAttrib('hiddenIdEnabled'));
        }

        return $this->_data;
    }

    /**
     *
     * @param \Zend_View_Interface $view
     * @return type
     */
    public function render(\Zend_View_Interface $view = null) {
        $this->setJQueryParam('data', $this->getAutocompleteData());
        $this->setJQueryParam('minLength', 0);

        return parent::render($view);
    }

}
