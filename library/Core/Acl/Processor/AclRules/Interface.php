<?php

interface Core_Acl_Processor_AclRules_Interface {
 
    /**
     * @return Core_Model_Db_Table_Rowset_Abstract
     */
    public function getAclRules();
    
}
