<?php

class Aktiv_Form_Element_CustomerAutocomplete extends ZendX_JQuery_Form_Element_AutoComplete
{
    /**
     *
     * @var array
     */
    protected $_data = array();

    /**
     *
     * @param mixed $spec
     * @param mixed $options
     */
    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);

        $this->setJQueryParam('data', $this->getAutocompleteData());
    }

    /**
     *
     * @return array
     */
    public function getAutocompleteData()
    {
        if(empty($this->_data)){
            $clientsModel = new Application_Model_Clients();
            $this->_data = $clientsModel->getClientsList('created DESC');
        }

        return $this->_data;
    }
}
