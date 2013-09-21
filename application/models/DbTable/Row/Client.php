<?php

class Application_Model_DbTable_Row_Client extends Core_Model_Db_Table_Row_Abstract {

    /**
     *
     * @return int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     *
     * @param int $id
     * @return Application_Model_DbTable_Row_Client
     */
    public function setId($id) {
        $this->id = (int) $id;
        return $this;
    }

    /**
     *
     * @return Application_Model_DbTable_Row_User
     */
    public function getBroker() {
        return $this->findParentRow('Application_Model_DbTable_Users');
    }

    /**
     *
     * @return type
     */
    public function getName() {
        return $this->name;
    }

    /**
     *
     * @param type $username
     * @return \Application_Model_DbTable_Row_Client
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     *
     * @param type $email
     * @return \Application_Model_DbTable_Row_Client
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     *
     * @return float
     */
    public function getFee($percent=true) {
        if ($percent === false) {
            return (float) $this->fee / 100;
        } else {
            return (float) $this->fee;
        }
    }

    /**
     *
     * @param float $fee
     * @return \Application_Model_DbTable_Row_Client
     */
    public function setFee($fee) {
        $this->fee = (float) $fee;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     *
     * @param type $created
     * @return \Application_Model_DbTable_Row_Client
     */
    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

}
