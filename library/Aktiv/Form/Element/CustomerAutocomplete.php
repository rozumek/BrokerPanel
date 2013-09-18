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
            $stockOrdersModel = new Application_Model_StockOrders();
            $this->_data = $stockOrdersModel->getCustomers();
        }
        
        return $this->_data;
    }
}
