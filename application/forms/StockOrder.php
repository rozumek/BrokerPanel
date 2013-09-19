<?php

class Application_Form_StockOrder extends Aktiv_User_Form
{

    public function init()
    {
        $this->initFormSettings();

        //add form elements
        $this->addCustomer()
                ->addTicker()
                ->addType()
                ->addNumber()
                ->addLimitValue()
                ->addLimitValueType()
                ->addStopLossValue()
                ->addStockPriceNow()
                ->addBroker(true)
                ->addNotes()
                ->addSave(true);       
    }

    /**
     *
     * @return \Application_Form_User
     */
    public function setEditForm()
    {
        $this->getElement('broker');

        if(!$this->getElement('timestamp')){
            $this->addTimestamp();
            $this->getElement('timestamp')->setOrder(0);
        }

        return $this;
    }

    /**
     *
     * @return \Application_Form_User
     */
    public function setFilterableForm()
    {
        parent::setFilterableForm();

        if(!$this->getElement('date_from')){
            $this->addDateFrom()
                    ->addDateTo();
        }

        $this->removeElement('type');
        $this->addType('select');

        $this->getElement('customer')->setRequired(false);
        $this->getElement('broker')->setRequired(false);

        $this->removeElements(
            array(
                'notes',
                'stockprice_now',
                'stoploss_value',
                'number',
                'ticker',
                'limit_value',
                'limit_value_type'
            )
        );

        return $this;
    }


}

