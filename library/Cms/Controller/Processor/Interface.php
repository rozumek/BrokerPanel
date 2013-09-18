<?php


interface Cms_Controller_Processor_Interface {
    
    /**
     * 
     * @param array $data
     */
    public function create($data);
    
    /**
     * 
     * @param array $data
     * @param int $id
     */
    public function update($data, $id);
    
    /**
     * 
     * @param int $id
     */
    public function delete($id);
    
    /**
     * 
     * @param int $id
     */
    public function changeState($id);        
}