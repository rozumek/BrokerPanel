<?php

class Application_Model_DbTable_Row_BlackboardEntry extends Core_Model_Db_Table_Row_Abstract {

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
     * @return Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setId($id) {
        $this->id = (int) $id;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     *
     * @param string $title
     * @return \Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     *
     * @param string $text
     * @return \Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getOrdering() {
        return (int) $this->ordering;
    }

    /**
     *
     * @param type $ordering
     * @return \Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setOrdering($ordering) {
        $this->ordering = (int) $ordering;
        return $this;
    }

    /**
     *
     * @return type
     */
    public function getActive() {
        return (int) $this->active;
    }

    /**
     *
     * @param type $active
     * @return \Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setActive($active) {
        $this->active = (int) $active;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDateFrom() {
        return $this->date_from;
    }

    /**
     *
     * @param string $date
     * @return \Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setDateFrom($date) {
        $this->date_from = $date;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getDateTo() {
        return $this->date_to;
    }

    /**
     *
     * @param string $date
     * @return \Application_Model_DbTable_Row_BlackboardEntry
     */
    public function setDateTo($date) {
        $this->date_to = $date;
        return $this;
    }


}
