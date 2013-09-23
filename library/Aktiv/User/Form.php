<?php

class Aktiv_User_Form extends Core_Form
{

    /**
     *
     * @return \Aktiv_Form
     */
    public function initFormSettings()
    {
        parent::initFormSettings();
        $this->setAttrib('id', 'stockOrderForm');

        return $this;
    }

    /**
     *
     * @return Aktiv_Form
     */
    public function addCustomer()
    {
        $customerName = new Aktiv_Form_Element_CustomerAutocomplete('customer');
        $customerName->setLabel('CUSTOMER')
                ->setRequired(true)
                ->setOrder(1)
                ->setAttrib('hiddenIdEnabled', true);
        $this->addElement($customerName);

        return $this;
    }

    /**
     *
     * @return Aktiv_Form
     */
    public function addType($elementType='radio')
    {

        if($elementType === 'radio'){
            $type = new Aktiv_Form_Element_StockOrderTypeRadio('type');
            $type->setValue(1);
        }else{
            $type = new Aktiv_Form_Element_StockOrderTypeSelect('type');
        }

        $type->setLabel('ORDER_TYPE')
                ->setRequired(true)
                ->setOrder(3);

        $this->addElement($type);

        return $this;
    }

    /**
     *
     * @return Aktiv_Form
     */
    public function addLimitValue()
    {
        $limitValue = new Zend_Form_Element_Text('limit_value');
        $limitValue
                //->setLabel('LIMIT_VALUE')
                ->setOrder(6)
                ->setRequired(true)
                ->addValidator(new Zend_Validate_Float());
        $this->addElement($limitValue);

        return $this;
    }

    /**
     *
     * @return Aktiv_Form
     */
    public function addLimitValueType()
    {
        $limitValue = new Aktiv_Form_Element_LimitValueType('limit_value_type');
        $limitValue->setLabel('LIMIT_VALUE')
                ->setOrder(5)
                ->setRequired(true)
                ->setValue(1);
        $this->addElement($limitValue);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addNumber()
    {
        $number = new Zend_Form_Element_Text('number');
        $number->setLabel('NUMBER')
                ->setOrder(4)
                ->setRequired(true)
                ->addValidator(new Zend_Validate_Int());
        $this->addElement($number);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addTicker()
    {
        $ticker = new Zend_Form_Element_Text('ticker');
        $ticker->setLabel('TICKER')
                ->setOrder(2);
        $this->addElement($ticker);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addStopLossValue()
    {
        $ticker = new Zend_Form_Element_Text('stoploss_value');
        $ticker->setLabel('STOPLOSS_VALUE')
                ->setOrder(7)
                ->setAttrib('warning', _T('STOPLOSS_WARNING'))
                ->setAttrib('id', 'stopLossValue')
                ->addValidator(new Zend_Validate_Float())
                ;
        $this->addElement($ticker);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addNotes()
    {
        $notes = new Zend_Form_Element_Textarea('notes');
        $notes->setLabel('NOTES')
                ->setOrder(10);
        $this->addElement($notes);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addStockPriceNow()
    {
        $stockprice = new Zend_Form_Element_Text('stockprice_now');
        $stockprice->setLabel('TRANSACTION_PRICE')
                ->setOrder(8)
                ->addValidator(new Zend_Validate_Float());
        $this->addElement($stockprice);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addBroker($acl=false)
    {
        $broker = null;

        if($acl === true  && !Core_Acl::isUserAllowed('default:stock-orders', 'select-broker')){
            $broker = new Zend_Form_Element_Hidden('broker');
        }else{
            $broker = new Core_Form_Element_User('broker');
            $broker->setLabel("BROKER")
                    ->setOrder(9);
        }

        $broker->setRequired(true);

        $this->addElement($broker);

        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addTimestamp()
    {
        $timestamp = new Core_Form_Element_Caption('timestamp');
        $timestamp->setLabel('TIMESTAMP')
                ->setOrder(0);
        $this->addElement($timestamp);

        return $this;
    }


    /**
     *
     * @return \Aktiv_Form
     */
    public function addDateFrom()
    {
        $date = new Core_Form_Element_SelectDate('date_from');
        $date->setLabel('DATE_FROM')
                ->setOrder(11);
        $this->addElement($date);
        return $this;
    }

    /**
     *
     * @return \Aktiv_Form
     */
    public function addDateTo()
    {
        $date = new Core_Form_Element_SelectDate('date_to');
        $date->setLabel('DATE_TO')
                ->setOrder(12);
        $this->addElement($date);
        return $this;
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    public function isValid($data) {
        //LIMIT_VALUE_B
        if($data['limit_value_type'] == 2) {
            $this->getElement('limit_value')->setRequired(false);
        }

        return parent::isValid($data);
    }

}
