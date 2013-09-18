<?php

class Cms_Form extends Core_Form 
{
    /**
     * 
     * @return \Cms_Form
     */
    public function addTitle()
    {
        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Title')
                ->addFilters(array(
                    new Zend_Filter_StripTags(),
                    new Zend_Filter_StringTrim()
                ))
                ->setRequired(true);                                              
        $this->addElement($title);
        
        return $this;
    }
    /**
     * 
     * @return \Cms_Form
     */
    public function addTopic()
    {
        $title = new Zend_Form_Element_Text('topic');
        $title->setLabel('Temat')
                ->addFilters(array(
                    new Zend_Filter_StripTags(),
                    new Zend_Filter_StringTrim()
                ))
                ->setRequired(true);                                              
        $this->addElement($title);
        
        return $this;
    }
    
    /**
     * 
     * @param bool $exists
     * @return \Cms_Form
     */
    public function addSlug($exists=false)
    {
        $slug = new Zend_Form_Element_Text('slug');
        $slug->setLabel('Alias')
                ->addFilter(new Core_Filter_Slugify());
        
        if($exists === false){
            $dbData1 = array(
                'table' => 'articles',
                'field' => 'slug'
            );
            
            $dbValidator = new Zend_Validate_Db_NoRecordExists($dbData1);
            $dbValidator->setMessage('Inny Artykuł posiada alias: %value%');
            $slug->addValidator($dbValidator);
            
            $dbData2 = array(
                'table' => 'routes',
                'field' => 'route'
            );
            
            $dbValidator = new Zend_Validate_Db_NoRecordExists($dbData2);
            $dbValidator->setMessage('Alias: %value% jest niedozwolony');
            $slug->addValidator($dbValidator);
        }
        
        $this->addElement($slug);
        
        return $this;
    }
    
    /**
     * 
     * @param bool $editor
     * @return \Cms_Form
     */
    public function addText($editor=true)
    {
        if($editor===true){
            $text = new Cms_Form_Element_Editor('text', array('editor' => 'tinyMce'));
        }else{
            $text = new Zend_Form_Element_Textarea('text');
        }
        
        $text->setLabel('Text')
                ->setRequired(true);
        $this->addElement($text);  
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addOwner()
    {
        $owner = new Core_Form_Element_User('owner');
        $owner->setLabel('Owner')                
                ->setRequired(true); 
        $this->addElement($owner);
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addCategory()
    {
        $category = new Cms_Form_Element_Article_Category('category');
        $category->setLabel('Category');
        $this->addElement($category);
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addPublished()
    {
        $active = new Zend_Form_Element_Select('active');
        $active->setLabel("Opublikowany")
            ->addMultiOption('0', 'Nie')
            ->addMultiOption('1', 'Tak');
        $this->addElement($active);
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addImage()
    {
        $banner = new Cms_Form_Element_Banner('image');
        $banner->setLabel('Baner')
                ->setRequired(true);
        $this->addElement($banner);
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addRoute()
    {
        $route = new Core_Form_Element_Route('route');
        $route->setLabel("Adres");
        $this->addElement($route);
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addHeight()
    {
        $height = new Zend_Form_Element_Text('height');
        $height->setLabel('Wysokość')
               ->addValidator(new Zend_Validate_Int())
                ->setRequired(true);                                              
        $this->addElement($height);
        
        return $this;
    }
    
    /**
     * 
     * @return \Cms_Form
     */
    public function addWidth()
    {
        $width = new Zend_Form_Element_Text('width');
        $width->setLabel('Szerokość')
               ->addValidator(new Zend_Validate_Int())
                ->setRequired(true);                                              
        $this->addElement($width);
        
        return $this;
    }
}
