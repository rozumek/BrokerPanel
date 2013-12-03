<?php

class Application_Model_DbTable_Blackboard extends Core_Model_Db_Table_Abstract
{
    /**
     *
     * @var string
     */
    protected $_name = 'blackboard';

    /**
     *
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    protected $_rowClass = 'Application_Model_DbTable_Row_BlackboardEntry';

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
            'columns'           => array('broker'),
            'refTableClass'     => 'Application_Model_DbTable_Users',
            'refColumns'        => array('id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE,
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
    protected function _buildQuery($filters = array(), $sort = null, $limit = null, $offset = null)
    {
        $query = $this->select(true)
                ->setIntegrityCheck(false)
                ->limit($offset, $limit)
                ;

        if(isset($filters['id']) && !empty($filters['id'])){
            if(is_array($filters['id'])){
                $query->where($this->_name.'.id IN ('. implode(',', $filters['id']).')');
            }else{
                $query->where($this->_name.'.id = ?', $filters['id']);
            }
        }

        if(isset($filters['title']) && !empty($filters['title'])){
            $query->where('LOWER(title) LIKE ?', '%'.strtolower($filters['title']).'%');
        }

        if(isset($filters['active']) && ($filters['active'] == '1' || $filters['active'] == '0')){
            $query->where('active = ?', $filters['active']);
        }

        if(!empty($filters['broker'])){
            $query->where('broker = ?', $filters['broker']);
        }

        if(!empty($sort)){
            $query->order($sort);
        } else {
            $query->order(array('ordering ASC', 'created DESC'));
        }

        return $query;
    }

}

