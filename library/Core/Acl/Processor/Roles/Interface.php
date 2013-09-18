<?php

interface Core_Acl_Processor_Roles_Interface {
    
    /**
     * @return Core_Model_Db_Table_Rowset_Abstract
     */
    public function getRoles();
    
}
