<?php

class Application_Model_DbTable_Row_StockOrder extends Core_Model_Db_Table_Row_Abstract
{        
    
    /**
     * 
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }
    
    /**
     * 
     * @param int $id
     * @return Application_Model_DbTable_Row_StockOrder
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }
    
    /**
     *
     * @param string $ruleKey
     * @param Zend_Db_Table_Select $select
     * @return Application_Model_DbTable_Row_Role
     */
    public function getBroker($ruleKey=null, Zend_Db_Table_Select $select=null)
    {
        return $this->findParentRow('Application_Model_DbTable_Users', $ruleKey, $select);
    }
    
    /**
     * 
     * @param int $broker
     */
    public function setBroker($broker)
    {
        $this->broker = (int)$broker;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * 
     * @param type $ucustomer
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * 
     * @param int $type
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getLimitValue()
    {    
       return $this->limit_value;
    }
    
    /**
     * 
     * @param int $value
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setLimitValue($value)
    {
        $this->limit_value = $value;
        return $this;
    }
    /**
     * 
     * @return float
     */
    public function getLimitValueType()
    {    
       return (float)$this->limit_value_type;
    }
    
    /**
     * 
     * @param float $value
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setLimitValueType($value)
    {
        $this->limit_value_type = (float)$value;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getNumber()
    {    
       return (int)$this->number;
    }
    
    /**
     * 
     * @param int $number
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setNumber($number)
    {
        $this->number = (int)$number;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getTicke()
    {    
       return $this->ticker;
    }
    
    /**
     * 
     * @param string $ticker
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setTicker($ticker)
    {
        $this->ticker = $ticker;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getStoplossValue()
    {    
       return (float)$this->stoploss_value;
    }
    
    /**
     * 
     * @param float $stoplossValue
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setStoplossValue($stoplossValue)
    {
        $this->stoploss_value = (float)$stoplossValue;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }
    
    /**
     * 
     * @param string $notes
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    
    /**
     * 
     * @return float
     */
    public function getStockpriceNow()
    {    
       return (float)$this->stockprice_now;
    }
    
    /**
     * 
     * @param float $stockprice_nowe
     * @return \Application_Model_DbTable_Row_StockOrder
     */
    public function setStockpriceNow($stockprice_nowe)
    {
        $this->stockprice_now = (float)$stockprice_nowe;
        return $this;
    }
}
