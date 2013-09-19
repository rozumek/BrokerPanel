<?php

class Application_Form_User extends Core_User_Form
{
    /**
     *
     * @var int
     */
    protected $_role = null;
    
    /**
     * 
     * @param int $role
     * @param array $options
     */
    public function __construct($role=null, $options = null) {
        $this->_role = $role;
        parent::__construct($options);
    }
    
    public function init()
    {
        $this->initFormSettings();
        
        //add form elements
        $this->addName()
            ->addEmail()
            ->addUsername()
            ->addPassword()
            ->addRepeatPassword()
            ->addRole($this->_role)
            ->addParent()
            ->addActive()
            ->addSave()
            ->addHiddenId();        
    }        
    
    /**
     * 
     * @return \Application_Form_User
     */
    public function setEditForm()
    {
        $this->addId(array('attribs' => array('disabled'=>true)));
        
        $this->getElement('username')
            ->removeValidator('Zend_Validate_Db_NoRecordExists');
        
        $this->getElement('password')->setRequired(false);
        
        $this->removeElement('password2');
        
        return $this;
    }
    
    /**
     * 
     * @return \Application_Form_User
     */
    public function setFilterableForm()
    {        
        parent::setFilterableForm();
        
        $this->getElement('role')->setRequired(false);
        $this->getElement('email')->setRequired(false);
        $this->getElement('username')->setRequired(false);
        $this->getElement('name')->setRequired(false);
        $this->getElement('active')->setRequired(false);
        
        $this->removeElements(
            array(
                'password',
                'password2',
            )
        );
        
        return $this;
    }


}

