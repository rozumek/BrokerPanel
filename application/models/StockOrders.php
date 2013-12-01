<?php

class Application_Model_StockOrders extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface
{

    /**
     * Date for starting calculation od fee income
     *
     * @var string
     */
    public static $_feeIncomeStartCalculation = '2013-10-01';

    /**
     *
     */
    public function __construct()
    {
        $this->_dbTable = new Application_Model_DbTable_StockOrders();
    }

    /**
     *
     * @param type $id
     * @return Application_Model_DbTable_Row_StockOrder | null
     */
    public function getStockOrder($id)
    {
        return $this->get($id);
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function stockOrderExists($id)
    {
        if((int) $id > 0){
            return $this->exists($id);
        }

        return false;
    }

    /**
     *
     * @param int $id
     */
    public function deleteStockOrder($id)
    {
        return $this->delete($id);
    }

    /**
     *
     * @param int $id
     * @return boolean
     */
    public function changeState($id)
    {
        throw new Exception('Unsupported action');
    }

    /**
     *
     * @param array|object $data
     * @return boolean
     */
    public function createStockOrder($data)
    {
        return $this->create($data);
    }

    /**
     *
     * @param array|object $data
     * @return boolean
     */
    public function create($data)
    {
        $stockOrder = $this->getEmptyRow();

        if($stockOrder instanceof Application_Model_DbTable_Row_StockOrder){
            $data = (array)$data;

            $stockOrder->setCustomer($data['customer']);
            $stockOrder->setType($data['type']);
            $stockOrder->setLimitValue(Core_Array::get($data, 'limit_value', 0));
            $stockOrder->setLimitValueType($data['limit_value_type']);
            $stockOrder->setNumber($data['number']);
            $stockOrder->setTicker($data['ticker']);
            $stockOrder->setStoplossValue($data['stoploss_value']);
            $stockOrder->setNotes($data['notes']);
            $stockOrder->setStockpriceNow($data['stockprice_now']);
            $stockOrder->setBroker($data['broker']);

            if(!empty($data['parent'])){
                $stockOrder->setParent($data['parent']);
            }

            return $this->save($stockOrder);
        }

        return false;
    }

     /**
     *
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateStockOrder(array $data, $id)
    {
        return $this->update($data, $id);
    }

    /**
     *
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id)
    {
        $stockOrder = $this->get($id);

        if($stockOrder instanceof Application_Model_DbTable_Row_StockOrder){
            $data = (array)$data;

            $stockOrder->setCustomer($data['customer']);
            $stockOrder->setType($data['type']);
            $stockOrder->setLimitValue($data['limit_value']);
            $stockOrder->setLimitValueType($data['limit_value_type']);
            $stockOrder->setNumber($data['number']);
            $stockOrder->setTicker($data['ticker']);
            $stockOrder->setStoplossValue($data['stoploss_value']);
            $stockOrder->setNotes($data['notes']);
            $stockOrder->setStockpriceNow($data['stockprice_now']);
            $stockOrder->setBroker($data['broker']);

            if (isset($data['timestamp'])) {
               $stockOrder->setTimestamp($data['timestamp']);
            }

            return $this->save($stockOrder);
        }

        return false;
    }

     /**
     *
     * @param int|string $year
     * @return object|null
     */
    public function getBrokerOfAYear($year=null)
    {
        if($year === null){
            $year = date('Y');
        }

        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    array(
                        'so1'=>'stock_orders'
                    ),
                    array(
                        'SUM(so1.stockprice_now*so1.number) as sum',
                        'so1.broker',
                        'SUM(so1.stockprice_now * so1.number * clients.fee / 100) as fee_income'
                    )
                )
                ->joinInner('users', 'users.id = so1.broker', 'users.name as broker_name')
                ->joinLeft('clients', 'clients.id = so1.customer', '')
                ->where('YEAR(so1.timestamp) = ?', $year)
                ->where('so1.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->group(array('so1.broker', 'YEAR(so1.timestamp)'))
                ->order(array('fee_income DESC', 'sum DESC'))
                ;

        $broker = $this->getDbTable()
                ->fetchRow($query);

        if($broker instanceof Zend_Db_Table_Row_Abstract){
            return (object)$broker->toArray();
        }

        return null;
    }

    /**
     *
     * @param int|string $month
     * @param int|string $year
     * @return object|null
     */
    public function getBrokerOfAMonth($month=null, $year=null)
    {
        if($month === null){
            $month = date('m');
        }

        if($year === null){
            $year = date('Y');
        }

        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    array(
                        'so1'=>'stock_orders'
                    ),
                    array(
                        'SUM(so1.stockprice_now*so1.number) as sum',
                        'so1.broker',
                        'SUM(so1.stockprice_now * so1.number * clients.fee / 100) as fee_income'
                    )
                )
                ->joinInner('users', 'users.id = so1.broker', 'users.name as broker_name')
                ->joinLeft('clients', 'clients.id = so1.customer', '')
                ->where('MONTH(so1.timestamp) = ?', $month)
                ->where('YEAR(so1.timestamp) = ?', $year)
                ->where('so1.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->group(array('so1.broker', 'MONTH(so1.timestamp)', 'YEAR(so1.timestamp)'))
                ->order(array('fee_income DESC', 'sum DESC'))
                ;

        $broker = $this->getDbTable()
                ->fetchRow($query);

        if($broker instanceof Zend_Db_Table_Row_Abstract){
            return (object)$broker->toArray();
        }

        return null;
    }

    /**
     *
     * @param int|string $week
     * @param int|string $year
     * @return object|null
     */
    public function getBrokerOfAWeek($week=null, $year=null)
    {
        if($week === null){
            $week = date('W');
        }

        if($year === null){
            $year = date('Y');
        }

        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    array(
                        'so1'=>'stock_orders'
                    ),
                    array(
                        'SUM(so1.stockprice_now*so1.number) as sum',
                        'so1.broker',
                        'SUM(so1.stockprice_now * so1.number * clients.fee / 100) as fee_income'
                    )
                )
                ->joinInner('users', 'users.id = so1.broker', 'users.name as broker_name')
                ->joinLeft('clients', 'clients.id = so1.customer', '')
                ->where('WEEKOFYEAR(so1.timestamp) = ?', $week)
                ->where('YEAR(so1.timestamp) = ?', $year)
                ->where('so1.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->group(array('so1.broker', 'WEEKOFYEAR(so1.timestamp)', 'YEAR(so1.timestamp)'))
                ->order(array('fee_income DESC', 'sum DESC'))
                ;

        $broker = $this->getDbTable()
                ->fetchRow($query);

        if($broker instanceof Zend_Db_Table_Row_Abstract){
            return (object)$broker->toArray();
        }

        return null;
    }

    /**
     *
     * @param int|string $day
     * @param int|string $year
     * @return object|null
     */
    public function getBrokerOfADay($day=null, $year=null)
    {
        if($day === null){
            $day = date('z')+1;
        }

        if($year === null){
            $year = date('Y');
        }

        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    array(
                        'so1'=>'stock_orders'
                    ),
                    array(
                        'SUM(so1.stockprice_now*so1.number) as sum',
                        'so1.broker',
                        'SUM(so1.stockprice_now * so1.number * clients.fee / 100) as fee_income'
                    )
                )
                ->joinInner('users', 'users.id = so1.broker', 'users.name as broker_name')
                ->joinLeft('clients', 'clients.id = so1.customer', '')
                ->where('DAYOFYEAR(so1.timestamp) = ?', $day)
                ->where('YEAR(so1.timestamp) = ?', $year)
                ->where('so1.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->group(array('so1.broker', 'DAYOFYEAR(so1.timestamp)', 'YEAR(so1.timestamp)'))
                ->order(array('fee_income DESC', 'sum DESC'))
                ;

        $broker = $this->getDbTable()
                ->fetchRow($query);

        if($broker instanceof Zend_Db_Table_Row_Abstract){
            return (object)$broker->toArray();
        }

        return null;
    }

    /**
     *
     * @param string $type
     * @param int $limit
     * @param int $offset
     * @return \Zend_Paginator|Core_Model_Db_Table_Rowset_Abstract
     */
    public function getBrokerRanks($data=array(), $type='year', $paginator=true, $limit=null, $offset=0)
    {
        $year = !empty($data['year'])?$data['year']:date('Y');

        $select = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    array(
                        'so1'=>'stock_orders'
                    ),
                    array(
                        'SUM(so1.stockprice_now*so1.number) as turnover',
                        'so1.broker',
                        'SUM(so1.stockprice_now * so1.number * clients.fee / 100) as fee_income'
                    )
                )
                ->joinInner('users', 'users.id = so1.broker', 'users.name as broker_name')
                ->joinLeft('clients', 'clients.id = so1.customer', '')
                ->where('YEAR(so1.timestamp) = ?', $year)
                ->where('so1.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->order(array('fee_income DESC', 'turnover DESC'))
                ;

        switch($type){
            case 'day':
                $day = !empty($data['day'])?$data['day']:(date('z')+1);
                $select->where('DAYOFYEAR(so1.timestamp) = ?', $day)
                        ->group(array('so1.broker', 'DAYOFYEAR(so1.timestamp)', 'YEAR(so1.timestamp)'));
                break;

            case 'week':
                $week = !empty($data['week'])?$data['week']:date('W');
                $select->where('WEEKOFYEAR(so1.timestamp) = ?', $week)
                        ->group(array('so1.broker', 'WEEKOFYEAR(so1.timestamp)', 'YEAR(so1.timestamp)'));
                break;

            case 'month':
                $month = !empty($data['month'])?$data['month']:date('m');
                $select->where('MONTH(so1.timestamp) = ?', $month)
                        ->group(array('so1.broker', 'MONTH(so1.timestamp)', 'YEAR(so1.timestamp)'));
                break;

            case 'year': default:
                $select->group(array('so1.broker', 'YEAR(so1.timestamp)'));
                break;
        }


        $ranking = null;

        if($paginator === true){
            $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
            $ranking = new Zend_Paginator($adapter);
        }else{
            $select = $this->getDbTable()
                    ->fetchAll($select, null, $offset, $limit);
        }

        return $ranking;
    }

    /**
     *
     * @return array
     */
    public function getCustomers()
    {
        $customers = array();
        $query = $this->getDbTable()
                ->select()
                ->distinct()
                ->from('stock_orders', 'customer');

        $rowset = $this->getDbTable()
                ->fetchAll($query)
                ->toArray();

        foreach ($rowset as $row) {
           $customers[] = $row['customer'];
        }

        return $customers;
    }

    /**
     *
     * @param int $week
     * @param int $year
     * @return array
     */
    public function getBrokerWeekRanks($week=null, $year=null)
    {
        if($week === null){
            $week = date('W');
        }

        if($year === null){
            $year = date('Y');
        }

        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    'stock_orders',
                    array(
                        'DATE_FORMAT(timestamp, \'%Y-%m-%d\') date'
                    )
                )
                ->joinLeft(
                    'clients',
                    'clients.id = stock_orders.customer',
                    'SUM(stock_orders.stockprice_now * stock_orders.number * clients.fee / 100) as fee_income'
                )
                ->joinLeft(
                    'users',
                    'users.id = stock_orders.broker',
                    'users.name as broker_name'
                )
                ->group(
                    array(
                        'stock_orders.broker',
                        'DATE_FORMAT(stock_orders.timestamp, \'%Y-%m-%d\')'
                    )
                )
                ->where('stock_orders.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->where('YEAR(stock_orders.timestamp) = ?', $year)
                ->where('WEEKOFYEAR(stock_orders.timestamp) = ?', $week)
                ->order(array('DATE_FORMAT(timestamp, \'%Y-%m-%d\')', 'broker_name'));

        $rowset = $this->getDbTable()
                ->fetchAll($query)
                ->toArray();

        return $rowset;
    }

    /**
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getBrokerMonthRanks($month=null, $year=null)
    {
        if($month === null){
            $month = date('m');
        }

        if($year === null){
            $year = date('Y');
        }

        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    'stock_orders',
                    array(
                        'DATE_FORMAT(timestamp, \'%Y-%m-%d\') date'
                    )
                )
                ->joinLeft(
                    'clients',
                    'clients.id = stock_orders.customer',
                    'SUM(stock_orders.stockprice_now * stock_orders.number * clients.fee / 100) as fee_income'
                )
                ->joinLeft(
                    'users',
                    'users.id = stock_orders.broker',
                    'users.name as broker_name'
                )
                ->group(
                    array(
                        'stock_orders.broker',
                        'DATE_FORMAT(stock_orders.timestamp, \'%Y-%m-%d\')'
                    )
                )
                ->where('stock_orders.timestamp >= ?', self::$_feeIncomeStartCalculation)
                ->where('YEAR(stock_orders.timestamp) = ?', $year)
                ->where('MONTH(stock_orders.timestamp) = ?', $month)
                ->order(array('DATE_FORMAT(timestamp, \'%Y-%m-%d\')', 'broker_name'));

        $rowset = $this->getDbTable()
                ->fetchAll($query)
                ->toArray();

        return $rowset;
    }

    /**
     *
     * @param string $dateFrom
     * @param string $dateTo
     * @return array
     */
    public function getCustomStatistics($dateFrom, $dateTo)
    {
        $query = $this->getDbTable()
                ->select(false)
                ->setIntegrityCheck(false)
                ->from(
                    'stock_orders',
                    array(
                        'DATE_FORMAT(timestamp, \'%Y-%m-%d\') date'
                    )
                )
                ->joinLeft(
                    'clients',
                    'clients.id = stock_orders.customer',
                    'SUM(stock_orders.stockprice_now * stock_orders.number * clients.fee / 100) as fee_income'
                )
                ->joinLeft(
                    'users',
                    'users.id = stock_orders.broker',
                    'users.name as broker_name'
                )
                ->group(
                    array(
                        'stock_orders.broker',
                        'DATE_FORMAT(stock_orders.timestamp, \'%Y-%m-%d\')'
                    )
                )
                ->where('DATE_FORMAT(stock_orders.timestamp, \'%Y-%m-%d\') >= ?', $dateFrom)
                ->where('DATE_FORMAT(stock_orders.timestamp, \'%Y-%m-%d\') <= ?', $dateTo)
                ->order(array('DATE_FORMAT(timestamp, \'%Y-%m-%d\')', 'broker_name'));

        $rowset = $this->getDbTable()
                ->fetchAll($query)
                ->toArray();

        return $rowset;
    }
}

