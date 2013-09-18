<?php

class Core_Form extends Zend_Form 
{
    
    protected $_attribs = array(
        'class' => array(
            'std-form'
        )
    );    
    
    /**
     * 
     * @param Zend_Db_Table_Row|object|array $data
     * @return Core_Form
     */
    public function setValues($data)
    {
        if($data){
            foreach($data as $name => $value){
                if($this->getElement($name)){
                    $this->getElement($name)->setValue($value);
                }
            } 
        }
        
        return $this;
    }
    
    /**
     * 
     * @param type $list
     * @return \Core_Form
     */
    public function removeElements($elements=array()) 
    {
        foreach($elements as $element){
            $this->removeElement($element);
        }
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function setFilterableForm() 
    {
        $this->addAttribs(array(
                'class' => 'filter-form',
                'id' => 'filterForm'
            ));
                       
        $this->getElement('submit')
                ->setLabel('FILTER');
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function setEditForm()
    {
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function initFormSettings()
    {
        $this->setMethod('post')
            ->setElementFilters(array(                   
                new Zend_Filter_StripTags(),
            ));                                
       
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function addActive($options=array())
    {   
        $active = new Core_Form_Element_Active('active');
        $active->setLabel("ACTIVE")
            ->setRequired(true);
        
        if(isset($options['attribs'])){
            $active->setAttribs($options['attribs']);
        }
        
        $this->addElement($active);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function addSubmit($setLast=true)
    {
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('SUBMIT');
        
        if($setLast === true){
            $submit->setOrder(1000);
        }
        
        $this->addElement($submit);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function addSave($setLast=true)
    {
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('SAVE');
        
        if($setLast === true){
            $submit->setOrder(1000);
        }
        
        $this->addElement($submit);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function addHiddenId()
    {
        $id = new Zend_Form_Element_Hidden('id');
        $this->addElement($id);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function addId($options=array())
    {
        $id = new Core_Form_Element_Id('id');
        $id->setLabel('ID')
                ->addValidator(new Zend_Validate_Int())
                ->setOrder(0);
        
        if(isset($options['attribs'])){
            $id->setAttribs($options['attribs']);
        }
        
        $this->addElement($id);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function addParams()
    {
        $params = new Cms_Form_Element_Params('params');
        $params->setDynamic(true)
                ->setLabel('PARAMS');                
        $this->addElement($params);
        
        return $this;
    }
    
    /**
     * 
     * @param bool $editor
     * @return \Core_Form
     */
    public function addText()
    {
        $text = new Zend_Form_Element_Textarea('text');
        $text->setLabel('TEXT')
                ->setRequired(true);
        $this->addElement($text);  
        
        return $this;
    }
    
    /**
     * 
     * @param bool $editor
     * @return \Core_Form
     */
    public function addDescription()
    {
        $text = new Zend_Form_Element_Textarea('description');
        $text->setLabel('DESCRIPTION');
        $this->addElement($text);  
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
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
     * @return \Core_Form
     */
    public function addSearch()
    {
        $search = new Zend_Form_Element_Text('search');
        $search->setOrder(0);
        $this->addElement($search);
        
        return $this;
    }
    
    /**
     * 
     * @return \Core_Form
     */
    public function setReadOnly()
    {
        foreach ($this->getElements() as $element) {
            if($element instanceof Zend_Form_Element){
                if($element instanceof Zend_Form_Element_Select){
                    $element->setAttribs(array(
                        'onfocus' => "this.defaultIndex=this.selectedIndex;",
                        'onchange' => "this.selectedIndex=this.defaultIndex;",
                        'class' => 'readonly'
                    ));
                }else if($element instanceof Zend_Form_Element_Submit){
                    $this->removeElement($element->getName());
                }else{
                    $element->setAttribs(
                            array(
                                'readonly' => true,
                                'class' => 'readonly'
                            )
                        );
                }
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getUnvalidatedElementsLabels()
    {
        $labels = array();
        
        foreach ($this->getMessages() as $name => $message){
            $element = $this->getElement($name);
            
            if($element instanceof Zend_Form_Element){
                $labels[] = $element->getLabel();
            }
        }
        
        return $labels;
    }
}
