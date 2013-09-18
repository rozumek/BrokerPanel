<?php

class Core_User_Form extends Core_Form
{
    /**
     * 
     * @return \Core_User_Form
     */
    public function addName()
    {
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('NAME')
            ->setRequired(true);
        $this->addElement($name);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addFirstName()
    {
        $name = new Zend_Form_Element_Text('first_name');
        $name->setLabel('FIRSTNAME')
            ->setRequired(true);
        $this->addElement($name);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addLastName()
    {
        $name = new Zend_Form_Element_Text('last_name');
        $name->setLabel('LASTNAME')
            ->setRequired(true);
        $this->addElement($name);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addUsername($exists=false)
    {
        $dbData = array(
            'table' => 'users',
            'field' => 'username'
        );
        
        if($exists === true){
            $dbValidator = new Zend_Validate_Db_RecordExists($dbData);            
        }else{
            $dbValidator = new Zend_Validate_Db_NoRecordExists($dbData);            
        }
        
        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('LOGIN')
            ->setRequired(true)
            ->addValidator($dbValidator);
        $this->addElement($username);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addPassword($required=true)
    {
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('PASSWORD')
            ->setRequired($required)
                ->addValidator(new Zend_Validate_StringLength(6));
        $this->addElement($password);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addRepeatPassword()
    {
        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('PASSWORD2')
            ->setRequired(true)
            ->addValidator(new Zend_Validate_Identical('password'));
        $this->addElement($password2);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addRole($role)
    {
        $role = new Core_Form_Element_Role('role', $role);
        $role->setLabel("ROLE")
            ->setRequired(true);
        $this->addElement($role);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addParent()
    {
        $role = new Core_Form_Element_User('parent');
        $role->setLabel("PARENT_USER");
        $this->addElement($role);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addEmail($exists=null, $request=null)
    {
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('EMAIL')
                ->setRequired(true)                
                ->addValidator(new Zend_Validate_EmailAddress()); 
        
        if($exists !== null){
            $dbData1 = array(
                'table' => 'users',
                'field' => 'email'
            );
            
            if($exists === true){
                $dbValidator1 = new Zend_Validate_Db_NoRecordExists($dbData1);
            }else if($exists === false){
                $dbValidator1 = new Zend_Validate_Db_RecordExists($dbData1);
            }
            
            $email->addValidator($dbValidator1);
        }
        
        if($request !== null){
            $dbData2 = array(
                'table' => 'user_account_requests',
                'field' => 'email'
            );
        
            if($exists === true){
                $dbValidator2 = new Zend_Validate_Db_NoRecordExists($dbData2);
            }else if($exists === false){
                $dbValidator2 = new Zend_Validate_Db_RecordExists($dbData2);
            }
            
            $email->addValidator($dbValidator2);
        }
        
        $this->addElement($email);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Addres_Form
     */
    public function addBirthdate()
    {
        $datepicker = new ZendX_JQuery_Form_Element_DatePicker(
            'datepicker'.uniqid(),
            array(
                'jQueryParams' => array(
                    'defaultDate' => date('Y-m-d'),
                    'dateFormat' => 'yy-mm-dd',
                    'showOn' => 'button',
                    'buttonImageOnly' => false,
                    'changeMonth' => true,
                    'changeYear' => true,
                ),
            )
        );
        $datepicker->setLabel('BIRTHDATE')
                ->setRequired(true);
        $this->addElement($datepicker);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_User_Form
     */
    public function addDeleted($options = array())
    {
        $active = new Zend_Form_Element_Select('deleted');
        $active->setLabel("ACTIVE")
            ->addMultiOption('', 'SELECT')
            ->addMultiOption('0', 'Nie')
            ->addMultiOption('1', 'Tak');
        
        if(isset($options['attribs'])){
            $active->setAttribs($options['attribs']);
        }
        
        $this->addElement($active);
        
        return $this;
    }
    
}
