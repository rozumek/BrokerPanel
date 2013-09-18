<?php

/**
 * If Zend_Registry::get('Zend_Translate') return object of type Zend_Translate,
 * than this function returns translated version of $message (if translation exists)
 * 
 * @param string $message
 * @return string
 */
function _T($message){
    if(Zend_Registry::isRegistered('Zend_Translate') && ($translator = Zend_Registry::get('Zend_Translate')) instanceof Zend_Translate){
       return $translator->translate($message);
    }
    
    return $message;
}