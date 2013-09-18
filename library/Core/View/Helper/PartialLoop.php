<?php

class Core_View_Helper_PartialLoop  extends Zend_View_Helper_PartialLoop
{
    /**
     *
     * @var int 
     */
    public $partialLimit = 0;
    
    /**
     *
     * @var int
     */
    public $partialCurrent = 0;
    
    /**
     *
     * @var type 
     */
    public $partialContCounter = 0;
    /**
     * 
     * @param  string $name Name of view script
     * @param  string|array|Zend_Paginator $module If $model is empty, and $module is an array,  
     * @param  array $model|Zend_Paginator Variables to populate in the view
     * @return string|Zend_View_Helper_Partial
     */
    public function partialLoop($name = null, $module = null, $model = null)
    {
        if($module instanceof Zend_Paginator){
            $this->partialCurrent = $module->getCurrentPageNumber();
            $this->partialLimit = $module->getItemCountPerPage();
            $this->partialContCounter = $this->partialCounter + (($this->partialCurrent-1)*$this->partialLimit);
        }else{
            $this->partialContCounter = $this->partialCounter;
        }
        
        $this->view->partialContCounter = $this->partialContCounter;

        return parent::partialLoop($name, $module, $model);
    }
}
