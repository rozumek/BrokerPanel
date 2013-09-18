<?php

class Core_Form_Element_Role extends Core_Form_Element_DbSelect
{
    
     /**
     *
     * @var sting 
     */
    protected $_table = 'roles';
    
    /**
     *
     * @var sting 
     */
    protected $_valueField = 'id';
    
    /**
     *
     * @var sting 
     */
    protected $_labelField = 'name';
    
    /**
     *
     * @var int
     */
    protected $_role = null;

    /**
     * 
     * @param string $spec
     * @param string $roleName
     * @param type $options
     */
    public function __construct($spec, $role, $options = null) {
        $this->_role = $role;
        parent::__construct($spec, $options);
    }
    
    /**
     * 
     */
    public function init() 
    {
        $this->_whereConditionMap = array(
            'cond' => array(
                'id != ?'
            ),
            'values' => array(
                Application_Model_Roles::getGuestRoleId()
            ),
        );
        
        //@todo fix me, cant be hadcoded
        if($this->_role != Application_Model_Roles::getSuperRoleId()){
            $this->_whereConditionMap['cond'][] = 'id != ?';
            $this->_whereConditionMap['values'][] = Application_Model_Roles::getSuperRoleId();
        }
        
        //@todo fix me, cant be hadcoded
        if($this->_role == 5){ //ASM
            $this->_whereConditionMap['cond'][] = 'id != ?';
            $this->_whereConditionMap['values'][] = Application_Model_Roles::getSuperRoleId();
            $this->_whereConditionMap['cond'][] = 'id != ?';
            $this->_whereConditionMap['values'][] = 6; //ADMIN
        }
        
    }

}
