<?php

class Utils_Password 
{

    /**
     * Constants
     */
    const VERY_STRONG = 0xF;
    const STRONG = 0x7;
    const MEDIUM = 0x3;
    const LIGHT = 0x1;
    const POOR = 0;
    
    /**
     * 
     * @param int $length
     * @param int $strength
     * @return string
     */
    public static function generate($length = 9, $strength = 0) 
    {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        
        if ($strength & 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= "AEUY";
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        
        return $password;
    }

}
