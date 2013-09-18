<?php

class Core_Export_Excel 
{
    
    /**
     *
     * @var string
     */
    protected $_filename = null;
    
    /**
     *
     * @var string 
     */
    protected $_storageDir = TMP_DIR;
    
    /**
     *
     * @var array
     */
    protected $_data = array();
    
    /**
     *
     * @var array 
     */
    protected $_headers = array();
    
    /**
     *
     * @var array
     */
    protected $_filterColumns = array();
    
    /**
     * 
     * @param type $filename
     */
    public function __construct($filename, $storageDir=TMP_DIR) 
    {
        $this->setFilename($filename);
        $this->setStorageDir($storageDir);
    }
    
    /**
     * 
     * @param array $cols
     * @return \Core_Export_Excel
     */
    public function setFilterColumns($cols)
    {
        $this->_filterColumns = $cols;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getFilterColumns()
    {
        return $this->_filterColumns;
    }
    
    /**
     * 
     * @param string $filename
     * @return \Core_Export_Excel
     */
    public function setFilename($filename)
    {
        $this->_filename = $filename;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getFullFilename()
    {
        return $this->_storageDir.'/'.$this->_filename;
    }

    /**
     * 
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }
    
    /**
     * 
     * @return string
     */
    public function getStorageDir()
    {
        return $this->_storageDir;
    }
    
    /**
     * 
     * @param string $dir
     * @return \Core_Export_Excel
     */
    public function setStorageDir($dir)
    {
        $this->_storageDir = realpath($dir);
        return $this;
    }
    
    /**
     * 
     * @param array $data
     * @return \Core_Export_Excel
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }
    
    /**
     * 
     * @param array $headers
     * @return \Core_Export_Excel
     */
    public function setHeaders(array $headers)
    {
        $this->_headers = $headers;
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }
    
    /**
     * 
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
    
    /**
     * 
     * @return array
     */
    protected function _getFileredData()
    {
        if(!empty($this->_filterColumns)){
            $data  = array();

            foreach ($this->getData() as $key => $row){
                foreach ($row as $key => $value){
                    if(!in_array($key, $this->_filterColumns)){
                        unset($row[$key]);
                    }
                }
                $data[] = $row;
            }

            return $data;
        }else{
            return $this->getData();
        }
    }
    
    /**
     * 
     * @return \Core_Export_Excel
     */
    public function export()
    {
        $filename = $this->getFullFilename();
        $data = $this->_getFileredData();
        
        if (false === realpath($filename)) {
            touch($filename);
            chmod($filename, 0777);
        }
        
        $handle = fopen(realpath($filename), "w");
        
        if(count($this->_headers) == count($data[0])){
            $data = array_merge(array($this->_headers), $data);
        }
        
        foreach ($data as $row) {                        
            fputcsv($handle, $this->_fixEncoding($row), "\t");
        }

        fclose($handle);
        
        return $this;
    }
    
    /**
     * 
     * @param array $row
     * @return array
     */
    protected function _fixEncoding(&$row)
    {
        foreach($row as &$item){
            $item = iconv("UTF-8", "CP1250//TRANSLIT", $item); 
        }
        
        return $row;
    }
}
