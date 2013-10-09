<?php

abstract class Core_Model_Db_Table_Rowset_Abstract extends Zend_Db_Table_Rowset_Abstract implements Core_Model_Interface {

    /**
     *
     * @var string
     */
    protected $_lastMessage = null;

    /**
     *
     * @var Zend_Exception
     */
    protected $_lastException = null;

    /**
     *
     * @param array $config
     */
    public function __construct(array $config = array()) {
        parent::__construct($config);
    }

    /**
     *
     * @param int $offset
     * @return \Core_Model_Db_Table_Rowset_Abstract
     */
    public function setPosition($offset) {
        if ($offset <= $this->count() - 1) {
            $this->_pointer = $offset;
        } else if ($offset > $this->count()) {
            $this->_pointer = $this->count() - 1;
        }

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getLastMessage() {
        return $this->_lastMessage;
    }

    /**
     *
     * @return Zend_Exception
     */
    public function getLastException() {
        return $this->_lastException;
    }

    /**
     *
     * @return type
     */
    public function toArray() {
        foreach ($this as $i => $row) {
            $this->_data[$i] = $row->toArray();
        }
        return $this->_data;
    }

    /**
     *
     * @return array
     */
    public function toArrayCollection() {
        $collection = array();

        foreach ($this as $item) {
            if ($item instanceof Core_Model_Db_Table_Row_Abstract) {
                $collection[] = $item;
            }
        }

        return $collection;
    }

    /**
     *
     * @param array $array
     * @return \Core_Model_Db_Table_Rowset
     */
    public function setRows(array $array = array()) {
        foreach ($array as $key => $row) {
            if (is_string($key)) {
                $this->setRow($row);
            } else {
                $this->setRow($row, (int) $key);
            }
        }

        return $this;
    }

    /**
     *
     * @param type $row
     * @return \Core_Model_Db_Table_Rowset
     */
    public function setRow($row, $position = null) {
        if ($row instanceof Core_Model_Db_Table_Row_Abstract) {
            if (is_int($position)) {
                $this->_rows[$position] = $row;
            } else {
                $this->_rows[] = $row;
            }

            $row = $row->toArray();
        }

        if (is_array($row)) {
            if (is_int($position)) {
                $this->_data[$position] = $row;
            } else {
                $this->_data[] = $row;
            }
        }

        $this->_count = count($this->_data);

        return $this;
    }

    /**
     *
     * @param string $rowClass
     * @return \Core_Model_Db_Table_Rowset
     */
    public function setRowClass($rowClass) {
        if (class_exists($rowClass)) {
            $this->_rowClass = $rowClass;
        }

        return $this;
    }

    /**
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDbAdapter() {
        return $this->getTable()
                        ->getAdapter();
    }

}
