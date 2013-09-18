<?php


class Core_Filter_Slugify extends Zend_Filter{
   
    /**
     *
     * @var array 
     */
    protected static $_replaceMap = array(
        'ą' => 'a',
        'ę' => 'e',
        'ł' => 'l',
        'ś' => 's',
        'ć' => 'c',
        'ź' => 'z',
        'ż' => 'z',
        ' ' => '-',
        'ó' => 'o'
    );
    
    /**
     * 
     * @param string $value
     * @return string
     */
    public function filter($value) {        
        $filter = new Zend_Filter_Alnum(true);
        $value = str_replace(
                array_keys(self::$_replaceMap), 
                array_values(self::$_replaceMap), 
                strtolower(trim($filter->filter(str_replace('-', ' ', $value))))
            );
        return $value;
    }
    
}
