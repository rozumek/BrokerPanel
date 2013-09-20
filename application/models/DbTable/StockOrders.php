<?php

class Application_Model_DbTable_StockOrders extends Core_Model_Db_Table_Abstract {

    /**
     *
     * @var string
     */
    protected $_name = 'stock_orders';

    /**
     *
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_StockOrder';

    /**
     *
     * @var array
     */
    protected $_dependentTables = array(
    );

    /**
     *
     * @var array
     */
    protected $_referenceMap = array(
        'Broker' => array(
            'columns' => array('broker'),
            'refTableClass' => 'Application_Model_DbTable_Users',
            'refColumns' => array('id'),
            'onDelete' => self::RESTRICT,
        ),
        'Client' => array(
            'columns' => array('customer'),
            'refTableClass' => 'Application_Model_DbTable_Clients',
            'refColumns' => array('id'),
            'onDelete' => self::RESTRICT,
        )
    );

    /**
     *
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return Zend_Db_Table_Select
     */
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null) {
        $query = $this->select(true)
                ->setIntegrityCheck(false)
                ->joinInner('users', 'users.id = ' . $this->_name . '.broker', 'users.name as broker_name')
                ->joinInner(
                    'clients',
                    'clients.id = ' . $this->_name . '.customer',
                    array(
                        'clients.name as customer_name',
                        'clients.fee as customer_fee',
                        '(' . $this->_name . '.stockprice_now * ' . $this->_name . '.number * clients.fee) as fee_income'
                    )
                )
                ->limit($offset, $limit)
        ;

        if (isset($filters['id']) && !empty($filters['id'])) {
            if (is_array($filters['id'])) {
                $query->where($this->_name . '.id IN (' . implode(',', $filters['id']) . ')');
            } else {
                $query->where($this->_name . '.id = ?', $filters['id']);
            }
        }

        if (isset($filters['customer']) && !empty($filters['customer'])) {
            $query->where('LOWER(customer) LIKE ?', '%' . strtolower($filters['customer']) . '%');
        }

        if (isset($filters['notes']) && !empty($filters['notes'])) {
            $query->where('LOWER(notes) LIKE ?', '%' . strtolower($filters['notes']) . '%');
        }

        if (isset($filters['ticker']) && !empty($filters['ticker'])) {
            $query->where('LOWER(ticker) LIKE ?', '%' . strtolower($filters['ticker']) . '%');
        }

        if (isset($filters['broker']) && !empty($filters['broker'])) {
            $query->where('broker = ?', $filters['broker']);
        }

        if (isset($filters['type']) && !empty($filters['type'])) {
            $query->where('type = ?', $filters['type']);
        }

        if (isset($filters['limit_value_type']) && !empty($filters['limit_value_type'])) {
            $query->where('limit_value_type = ?', $filters['limit_value_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('timestamp >= ?', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('timestamp < DATE_ADD(?, INTERVAL 1 DAY)', $filters['date_to']);
        }

        if (!empty($sort)) {
            $query->order($sort);
        } else {
            $query->order('timestamp DESC');
        }

        return $query;
    }

}

