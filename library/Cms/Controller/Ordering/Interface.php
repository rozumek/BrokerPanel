<?php

interface Cms_Controller_Ordering_Interface {
 
    /**
     * 
     * @param int $id
     */
    public function orderUp($id);
    
    /**
     * 
     * @param int $id
     */
    public function orderDown($id);
    
    /**
     * 
     * @param int $id
     */
    public function reorder($menuid=null);
    
}