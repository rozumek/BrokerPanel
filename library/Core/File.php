<?php

class Core_File {

    /**
     *
     * @param array|string $folderPath
     * @param int $mode
     * @param bool $recursive
     * @param resource $context
     * @return boolean
     */
    public static function mkdir($folderPath, $mode=0777, $recursive=true) {
        if (is_array($folderPath)) {
            foreach($folderPath as $folder) {
                if(!self::mkdir($folder, $mode, $recursive)){
                    return false;
                }
            }
        } else if(is_string($folderPath)) {
            if(!realpath($folderPath)) {
                return mkdir($folderPath, $mode, $recursive);
            }
        } else {
            return false;
        }

        return true;
    }

}
