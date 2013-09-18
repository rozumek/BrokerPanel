<?php

class Cms_Dashboard {
    
    /**
     * Icon sizes
     */
    const SIZE_BIG = 'big';
    const SIZE_SMALL = 'small';
    
    /**
     *
     * @var Cms_Dashboard 
     */
    protected static $_instance = array();
    
    /**
     *
     * @var array 
     */
    protected $_buttons = array();
    
    /**
     *
     * @var string
     */
    protected $_name = 'dashboard';
    
    /**
     *
     * @var string
     */
    protected $_activeButtonTemplate = Cms_Dashboard::SIZE_BIG;

    /**
     *
     * @var array
     */
    protected $_allowedDashBoardButtonTypes = array(
        'alert',
        'archive',
        'basket',
        'calculator',
        'calendar',
        'cancel',
        'cart',
        'chat',
        'check',
        'color-picker',
        'comment',
        'connect',
        'construction',
        'controls',
        'copy',
        'cut',
        'dashboard',
        'database',
        'display',
        'document',
        'door',
        'edit',
        'equalizer',
        'export',
        'favourite',
        'file',
        'file-edit',
        'file-remove',
        'fire',
        'folder',
        'folder-open',
        'help',
        'idea',
        'login',
        'logout',
        'messages',
        'movie',
        'photo',
        'pollution',
        'preferences',
        'print',
        'settings',
        'search',
        'sitemap',
        'standby',
        'star',
        'statics',
        'support',
        'trash',
        'user',
        'users',
        'volume',
        'web',
        'wizard',
    );
    
    /**
     *
     * @var array
     */
    protected $_buttonTemplates = array( 
        Cms_Dashboard::SIZE_BIG => array(
            'url' => null,
            'headText' => null,
            'subText' => '',
            'type' => 'support',
            'size' => Cms_Dashboard::SIZE_BIG,
            'titleTag' => '',
            'attribs' => array()
        ),
        Cms_Dashboard::SIZE_SMALL => array(
            'url' => null,
            'headText' => null,
            'subText' => '',
            'type' => 'support',
            'size' => Cms_Dashboard::SIZE_BIG,
            'titleTag' => '',
            'attribs' => array()
        )
    );
    
    /**
     *
     * @var int
     */
    protected $_buttonsLimit = 5;
    
    /**
     *
     * @var int
     */
    protected $_buttonsCount = 0;
    
    /**
     * 
     * @param string $name
     * @param string $template
     */
    public function __construct($name='dashboard', $template=null) {
        if($name !== null && is_string($name)){
            $this->_name = $name;
        }
        
        if($template !== null && is_string($template)){
            $this->_activeButtonTemplate = $template;
        }
    }
    
    /**
     * 
     * @return int
     */
    public function getButtonsLimit(){
        return (int)$this->_buttonsLimit;
    }
    
    /**
     * 
     * @param int $limit
     * @return \Cms_Dashboard
     */
    public function setButtonsLimit($limit){
        $this->_buttonsLimit = $limit;
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getButtonsCount(){
        return (int) $this->_buttonsCount;
    }
    
    /**
     * 
     * @param string $name
     * @return Cms_Dashboard
     */
    public static function getIstance($name='dashboard'){
        if($name === null){
            $name = 'dashboard';
        }
        
        if(!self::instanceExists($name)){
            self::$_instance[$name] = new self($name);
        }
        
        return self::$_instance[$name];
    }
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    public static function instanceExists($name){
        if(isset(self::$_instance[$name]) && self::$_instance[$name] instanceof Cms_Dashboard){
            return true;
        }
        
        return false;
    }
    
    /**
     * 
     * @param Cms_Dashboard $instance
     * @param string $name
     */
    public static function setInstance(Cms_Dashboard $instance, $name=null){
        if($name === null){
            $name = 'dashboard';
        }
        
        self::$_instance[$name] = $instance;
    }

    /**
     * 
     * @return array
     */
    public function getButtonTypes(){
        return $this->_allowedDashBoardButtonTypes;
    }
    
    /**
     * 
     * @return array
     */
    public function getButtonSizes(){
        return array(
            Cms_Dashboard::SIZE_BIG, 
            Cms_Dashboard::SIZE_SMALL
        );
    }
    
    /**
     * 
     * @return array
     */
    public function getButtons(){
        return $this->_buttons;
    }
    
    /**
     * 
     * @param string $template
     * @return \Cms_Dashboard
     */
    public function setActiveButtonTemplate($template){
        if(key_exists($template, $this->_buttonTemplates)){
            $this->_activeButtonTemplate = $template;
        }
        
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getActiveButtonTemplate(){
        return $this->_activeButtonTemplate;
    }
    
    /**
     * 
     * @param string $url
     * @param string $headText
     * @param string $subText
     * @param string $type
     * @param string $size
     * @param string $titleTag
     * @param array $attribs
     * @return \Cms_Dashboard
     */
    public function appendStdButton($url, $headText, $subText='', $type='support', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){
        if($this->_buttonsCount < $this->_buttonsLimit){
            $this->_buttons[] = $this->_createButton(array(
                'url' => $url, 
                'headText' => $headText, 
                'subText' => $subText, 
                'type' => $type, 
                'size' => $size, 
                'titleTag' => $titleTag, 
                'attribs' => $attribs
            ));
        }
        
        $this->_buttonsCount++;
        
        return $this;
    }
    
    /**
     * 
     * @param array $fields
     * @param string $template
     * @return array
     */
    protected  function _createButton($fields, $template = Cms_Dashboard::SIZE_BIG){
        if(!isset($this->_buttonTemplates[$template])){
            $template = Cms_Dashboard::SIZE_BIG;
        }
        
        $button = $this->_buttonTemplates[$template];
        
        return array_merge($button, array_intersect_key($fields, $button));
    }
    
    /**
     * 
     * @return \Cms_Dashboard
     */
    public function clearButtons(){
        $this->_buttons = array();
        return $this;
    }
    
    /**
     * 
     * @param int $index
     * @return \Cms_Dashboard
     */
    public function clearButton($index){
        if(isset($this->_buttons[$index])){
            unset($this->_buttons[$index]);
            $this->_buttons = array_values($this->_buttons);
        }
        
        return $this;
    }    

    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendAddButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){        
        return $this->appendStdButton($url, 'Add', $subText, 'file', $attribs, $size, $titleTag);
    }
    
    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendClearButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){        
        return $this->appendStdButton($url, 'Clear', $subText, 'trash', $attribs, $size, $titleTag);
    }
    
    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendEditButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){  
        $class = 'supply-id';
        
        if(isset($attribs['class'])){
            if(is_array($attribs['class'])){
                $attribs['class'][] = $class;
            }else if(is_string($attribs['class'])){
                $attribs['class'] = $class.' '.$attribs['class'];
            }
        }else{
            $attribs['class'] = array($class);
        }
        
        return $this->appendStdButton($url, 'Edit', $subText, 'edit', $attribs, $size, $titleTag);
    }
    
    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendChangeStateButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){  
        $class = 'supply-id-list';
        
        if(isset($attribs['class'])){
            if(is_array($attribs['class'])){
                $attribs['class'][] = $class;
            }else if(is_string($attribs['class'])){
                $attribs['class'] = $class.' '.$attribs['class'];
            }
        }else{
            $attribs['class'] = array($class);
        }
        
        return $this->appendStdButton($url, 'Change State', $subText, 'idea', $attribs, $size, $titleTag);
    }
    
    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendDeleteButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){  
        $class = 'supply-id-list';
        
        if(isset($attribs['class'])){
            if(is_array($attribs['class'])){
                $attribs['class'][] = $class;
            }else if(is_string($attribs['class'])){
                $attribs['class'] = $class.' '.$attribs['class'];
            }
        }else{
            $attribs['class'] = array($class);
        }
        
        return $this->appendStdButton($url, 'Delete', $subText, 'trash', $attribs, $size, $titleTag);
    }
    
    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendViewButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){
        $class = 'supply-id';
        
        if(isset($attribs['class'])){
            if(is_array($attribs['class'])){
                $attribs['class'][] = $class;
            }else if(is_string($attribs['class'])){
                $attribs['class'] = $class.' '.$attribs['class'];
            }
        }else{
            $attribs['class'] = array($class);
        }
        
        return $this->appendStdButton($url, 'View', $subText, 'display', $attribs, $size, $titleTag);
    }
    
    /**
     * 
     * @param string $url
     * @param string $subText
     * @param array $attribs
     * @param string $size
     * @param string $titleTag
     * @return string
     */
    public function appendListButton($url, $subText='', $attribs=array(), $size=Cms_Dashboard::SIZE_BIG, $titleTag=null){
        return $this->appendStdButton($url, 'List', $subText, 'preferences', $attribs, $size, $titleTag);
    }
}
