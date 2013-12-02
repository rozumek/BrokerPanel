<?php

class Application_Model_Blackboard extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface {

    /**
     *
     */
    public function __construct() {
        $this->_dbTable = new Application_Model_DbTable_Blackboard();
    }

    /**
     *
     * @param type $id
     * @return Application_Model_DbTable_Row_BlackboardEntry | null
     */
    public function getBlackboardEntry($id) {
        return $this->get($id);
    }

    /**
     *
     * @return Zend_Db_Table_Select
     */
    protected function _getActiveBlackboardQuery() {
        $date = date('Y-m-d');

        (string)$query = $this->getDbTable()
                ->select()
                ->where('active=1')
                ->where('date_from <= ? OR date_from = \'0000-00-00 00:00:00\'', $date)
                ->where('date_to >= ? OR date_to = \'0000-00-00 00:00:00\'', $date)
                ->order('ordering ASC');

        return $query;
    }


    /**
     *
     * @return Core_Model_Db_Table_Rowset_Abstract
     */
    public function getActiveBlackboard() {
        $query = $this->_getActiveBlackboardQuery();
        $rowset = $this->getDbTable()->fetchAll($query);

        return $rowset;
    }

    /**
     *
     * @param int $id
     * @return Application_Model_DbTable_Row_BlackboardEntry
     */
    public function getActiveBlackboardEntry($id) {
        $query = $this->_getActiveBlackboardQuery();
        $query->where('id=?', $id);
        $entry = $this->getDbTable()->fetchRow($query);

        return $entry;
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function clientExists($id) {
        if ((int) $id > 0) {
            return $this->exists($id);
        }

        return false;
    }

    /**
     *
     * @param int $id
     */
    public function deleteBlackboardEntry($id) {
        return $this->delete($id);
    }

    /**
     *
     * @param array|object $data
     * @return boolean
     */
    public function createBlackboardEntry($data) {
        return $this->create($data);
    }

    /**
     *
     * @param array|object $data
     * @return boolean
     */
    public function create($data) {
        $entry = $this->getEmptyRow();

        if ($entry instanceof Application_Model_DbTable_Row_BlackboardEntry) {
            $data = (array) $data;
            $entry->setText($data['text']);
            $entry->setTitle(Core_Array::get($data, 'title', ''));
            $entry->setActive(Core_Array::get($data, 'active', 0));
            $entry->setOrdering(Core_Array::get($data, 'ordering', 0));

            if (!empty($data['date_from'])) {
                $entry->setDateFrom($data['date_from']);
            }

            if (!empty($data['date_to'])) {
                $entry->setDateTo($data['date_to']);
            }

            return $this->save($entry);
        }

        return false;
    }

    /**
     *
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateBlackboardEntry(array $data, $id) {
        return $this->update($data, $id);
    }

    /**
     *
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id) {
        $entry = $this->get($id);

        if ($entry instanceof Application_Model_DbTable_Row_BlackboardEntry) {
            $data = (array) $data;

            if (array_key_exists('title', $data)) {
                $entry->setTitle($data['title']);
            }
            if (array_key_exists('text', $data)) {
                $entry->setText($data['text']);
            }
            if (array_key_exists('active', $data)) {
                $entry->setActive($data['active']);
            }
            if (array_key_exists('ordering', $data)) {
                $entry->setOrdering(Core_Array::get($data, 'ordering', 0));
            }
            if (array_key_exists('date_from', $data)) {
                if(empty($data['date_from'])) {
                    $entry->setDateFrom('0000-00-00 00:00:00');
                } else {
                    $entry->setDateFrom($data['date_from']);
                }
            }
            if (array_key_exists('date_to', $data)) {
                if(empty($data['date_to'])) {
                    $entry->setDateTo('0000-00-00 00:00:00');
                } else {
                    $entry->setDateTo($data['date_to']);
                }
            }

            return $this->save($entry);
        }

        return false;
    }

    /**
     *
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return \Core_Model_Db_Table_Rowset
     */
    public function getBlackboard($filters = array(), $sort = null, $limit = null, $offset = 0) {
        return $this->getRowset($filters, $sort, $limit, $offset);
    }

    /**
     *
     * @param type $id
     * @throws Core_Exception
     */
    public function changeState($id) {
        $entry = $this->getBlackboardEntry($id);

        if ($entry instanceof Application_Model_DbTable_Row_BlackboardEntry) {
            $entry->setActive(!$entry->getActive());
            return $this->save($entry);
        }

        return false;
    }

}
