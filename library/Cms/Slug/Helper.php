<?php

class Cms_Slug_Helper {
            
    /**
     * 
     * @param string $title
     * @return string
     */
    static public function generateFrom($title){
        $filter = new Core_Filter_Slugify();
        $slug = $filter->filter($title);                
        return $slug;
    }
    
}
