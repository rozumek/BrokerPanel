<?php

class Application_Form_Client extends Core_User_Form {

    public function init() {
        $this->initFormSettings();

        //add form elements
        $this->addName()
                ->addEmail()
                ->addFee()
                ->addSave()
                ->addBroker()
                ->addHiddenId();
    }

    /**
     * 
     * @param type $exists
     * @param type $request
     * @return \Application_Form_User
     */
    public function addEmail($exists = null, $request = null) {
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('EMAIL')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_EmailAddress());

        $this->addElement($email);

        return $this;
    }

    /**
     * 
     * @return \Application_Form_User
     */
    public function addFee() {
        $fee = new Zend_Form_Element_Text('fee');
        $fee->setLabel('FEE')
                ->setRequired(true)
                ->addValidator(new Zend_Validate_Float());
        
        return $this;
    }
    
    /**
     * 
     * @return \Application_Form_User
     */
    public function addBroker() {
        $role = new Core_Form_Element_User('broker');
        $role->setLabel("BROKER");
        $this->addElement($role);
        
        return $this;
    }

    /**
     * 
     * @return \Application_Form_User
     */
    public function setEditForm() {
        $this->getElement('name')
                ->removeValidator('Zend_Validate_Db_NoRecordExists');

        return $this;
    }

    /**
     * 
     * @return \Application_Form_User
     */
    public function setFilterableForm() {
        parent::setFilterableForm();

        $this->getElement('email')->setRequired(false);
        $this->getElement('name')->setRequired(false);
        $this->getElement('broker')->setRequired(false);

        $this->removeElements(
                array(
                    'fee',
                    'password2',
                )
        );

        return $this;
    }

}

