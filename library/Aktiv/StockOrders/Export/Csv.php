<?php

class Aktiv_StockOrders_Export_Csv extends Core_Export_Csv
{
    /**
     *
     * @var array
     */
    protected $_headers = array(
        'Timestamp',
        'Customer',
        'Order Type',
        'Limit Value',
        'Limit Value Type',
        'Volume',
        'Ticker',
        'Stoploss Value',
        'Transaction Price',
        'Fee Income',
        'Broker',
        'Notes'
    );

    /**
     *
     * @var array
     */
    protected $_filterColumns = array(
        'timestamp',
        'customer_name',
        'type',
        'limit_value',
        'limit_value_type',
        'number',
        'ticker',
        'stoploss_value',
        'stockprice_now',
        'fee_income',
        'broker_name',
        'notes'
    );

    /**
     *
     * @param array $row
     * @return array
     */
    protected function _prepareData(&$row)
    {
        foreach($row as $key => $value){
            switch ($key){
                case 'type':
                    $row[$key] = _T(Aktiv_Dictionary_StockOrderTypes::getInstance()->getByCode($value));
                    break;

                case 'limit_value_type':
                    $row[$key] = _T(Aktiv_Dictionary_LimitValueTypes::getInstance()->getByCode($value));
                    break;
            }
        }

        return parent::_prepareData($row);
    }
}
