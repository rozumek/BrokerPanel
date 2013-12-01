<?php

class Application_Model_Clients extends Core_Model_Db_Abstract implements Cms_Controller_Processor_Interface {

    /**
     *
     */
    public function __construct() {
        $this->_dbTable = new Application_Model_DbTable_Clients();
    }

    /**
     *
     * @param type $id
     * @return Application_Model_DbTable_Row_Client | null
     */
    public function getClient($id) {
        return $this->get($id);
    }

    /**
     *
     * @param string $name
     * @return Application_Model_DbTable_Row_Client | null
     */
    public function getClientByName($name, $returnEmpty = false) {
        return $this->getFirstWhere('name=?', $name, $returnEmpty);
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function clientExists($id) {
        if ((int) $id > 0) {
            return $this->exists($id);
        } else if (is_string($id)) {
            return (bool) $this->getClientByName($id);
        }

        return false;
    }

    /**
     *
     * @param int $id
     */
    public function deleteClient($id) {
        return $this->delete($id);
    }

    /**
     *
     * @param array|object $data
     * @return boolean
     */
    public function createClient($data) {
        return $this->create($data);
    }

    /**
     *
     * @param array|object $data
     * @return boolean
     */
    public function create($data) {
        $client = $this->getEmptyRow();

        if ($client instanceof Application_Model_DbTable_Row_Client) {
            $data = (array) $data;
            $client->setName($data['name']);
            $client->setEmail($data['email']);
            $client->setFee(Core_Array::get($data, 'fee', 0.3));
            $client->setBroker($data['broker']);

            return $this->save($client);
        }

        return false;
    }

    /**
     *
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function updateClient(array $data, $id) {
        return $this->update($data, $id);
    }

    /**
     *
     * @param array $data
     * @param int $id
     * @return boolean
     */
    public function update($data, $id) {
        $client = $this->get($id);

        if ($client instanceof Application_Model_DbTable_Row_Client) {
            $data = (array) $data;

            if (!empty($data['name'])) {
                $client->setName($data['name']);
            }
            if (!empty($data['email'])) {
                $client->setEmail($data['email']);
            }
            if (!empty($data['fee'])) {
                $client->setFee($data['fee']);
            }
            if (!empty($data['broker'])) {
                $client->setBroker($data['broker']);
            }

            return $this->save($client);
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
    public function getClients($filters=array(), $sort=null, $limit=null, $offset=0) {
        return $this->getRowset($filters, $sort, $limit, $offset);
    }

    /**
     *
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getClientsList($sort=null, $limit=null, $offset=0, $forAutocomplete=true) {
        $list = array();

        foreach ($this->getClients(array(), $sort, $limit, $offset) as $client) {
            if ($client instanceof Application_Model_DbTable_Row_Client) {
                if ($forAutocomplete) {
                    $list[] = array(
                        'label' => $client->getName(),
                        'value' => $client->getId()
                    );
                } else {
                    $list[] = $client->getName();
                }
            }
        }

        return $list;
    }


    /**
     *
     * @param type $id
     * @throws Core_Exception
     */
    public function changeState($id) {
        throw new Core_Exception('Not supported');
    }
}

