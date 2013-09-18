<?php

interface Core_Model_Db_PaginatorList_Interface {

    /**
     * 
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @return Zend_Paginator
     */
    public function getPaginatorList($filters = array(), $sort=null, $limit=null, $offset=0);
}
