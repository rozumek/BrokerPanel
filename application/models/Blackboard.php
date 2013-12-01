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
     * @return Core_Model_Db_Table_Rowset_Abstract
     */
    public function getActiveBlackboard() {
        $date = date('Y-m-d');

        $query = $this->getDbTable()
                ->select()
                ->where('active=1')
                ->where('date_from >= ?', $date)
                ->where('date_to <= ?', $date)
                ->order('ordering ASC');

        $rowset = $this->getDbTable()->fetchAll($query);

        return $rowset;
    }

    /**
     *
     * @param int $id
     * @return Application_Model_DbTable_Row_BlackboardEntry
     */
    public function getActiveBlackboardEntry($id) {
        $date = date('Y-m-d');

        $query = $this->getDbTable()
                ->select()
                ->where('id=?', $id)
                ->where('active=1')
                ->where('date_from >= ?', $date)
                ->where('date_to <= ?', $date);

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
            $entry->setTitle($data['title']);
            $entry->setText($data['text']);
            $entry->setActive(Core_Array::get($data, 'active', 0));
            $entry->setDateFrom($data['date_from']);
            $entry->setDateTo($data['date_to']);

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
                $entry->setName($data['title']);
            }
            if (array_key_exists('text', $data)) {
                $entry->setEmail($data['text']);
            }
            if (!empty($data['active'])) {
                $entry->setFee($data['active']);
            }
            if (!empty($data['date_from'])) {
                $entry->setBroker($data['date_from']);
            }
            if (!empty($data['date_to'])) {
                $entry->setBroker($data['date_to']);
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
        $entry = $this->getUser($id);

        if ($entry instanceof Application_Model_DbTable_Row_User) {
            $entry->setActive(!$entry->getActive());
            return $this->save($entry);
        }

        return false;
    }

}
