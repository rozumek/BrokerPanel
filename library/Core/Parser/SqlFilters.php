<?php

class Core_Parser_SqlFilters {

    /**
     *
     * @var array
     */
    protected $_parseRulePattern = array(
        'name',
        'value',
        'sign'
    );
    
    /**
     *
     * @var array
     */
    protected $_allowedSigns = array(
        '>', 
        '<', 
        '=', 
        'LIKE', 
        '>=', 
        '<=',
        '!=',
        '<>',
        'IS',
        'IS NOT'
    );

    protected $_parseRuleExploder = ':';
    
    /**
     *
     * @var string
     */
    protected $_nameKey = 'name';

    /**
     *
     * @var string
     */
    protected $_valueKey = 'value';

    /**
     *
     * @var string
     */
    protected $_signKey = 'sign';

    /**
     *
     * @var string
     */
    protected $_defaultSign = '=';
    
    /**
     *
     * @var string
     */
    protected $_quoteSign = "'";

    /**
     *
     * @var bool 
     */
    protected $_filterPatternEnable = true;

    /**
     *
     * @var array
     */
    protected $_includeSqlRule = true;
    
    /**
     *
     * @var array
     */
    protected $_parsedData = array();

    /**
     *
     * @var array
     */
    protected $_defafaultSqlRuleImploder = ' AND ';
    
    /**
     * 
     * @param array|string $filters
     * @return bool
     */
    public function isParsable($filter) {
        $valid = true;
        
        if(empty($filter)) {
            return false;
        }
        
        if(is_array($filter)) {
            foreach($filter as $fil) {             
                if(!$this->isParsable($fil)) {
                    $valid = false;
                    break;
                }
            }
        } else if(is_string($filter)) {
            $exploded = explode($this->_parseRuleExploder, $filter);
            $valid = (count($exploded) >= 2)?true:false;
        } else {
            return false;
        }
        
        return $valid;
    }
    
    /**
     * 
     * @param array $filters
     * @return array
     */
    public function parseFilters(array $filters) {
        foreach ($filters as $filter) {
            if (is_string($filter)) {
                $this->parseInput($filter);
            }
        }

        return $this->_parsedData;
    }

    /**
     * 
     * @param string $input
     */
    public function parseInput($input) {
        $inputExploded = explode($this->_parseRuleExploder, $input);
        
        if(count($inputExploded) >= 2) {            
            if ($this->isFilterPatternEnabled()) {
                $output = array();
                
                foreach ($this->_parseRulePattern as $index => $name) {
                    if(!isset($inputExploded[$index])) {
                        $output[$name] = $this->_defaultSign;
                    } else {
                        $output[$name] = $inputExploded[$index];                        
                    }
                }
                
                if($this->_includeSqlRule) {
                    $output['sql'] = $this->_assembleSqlRule($output);
                }
                
                if(in_array($output[$this->_signKey], $this->_allowedSigns)){
                    $this->_parsedData[] = $output;
                }
            } else {
                $this->_parsedData [$inputExploded[0]] = $inputExploded[1];
            }            
        }

        return $this;
    }

    /**
     * 
     * @param array $output
     * @return string
     */
    protected function _assembleSqlRule($output) {
        if($output[$this->_valueKey] == 'NULL') {
            return $output[$this->_nameKey].' '.$output[$this->_signKey].' '.$output[$this->_valueKey];
        } else {
            return $output[$this->_nameKey].' '.$output[$this->_signKey].' '.$this->_quote($output[$this->_valueKey]);
        }
    }
    
    /**
     * 
     * @return bool
     */
    public function isFilterPatternEnabled() {
        return $this->_filterPatternEnable === true;
    }
    
    /**
     * 
     * @param string $value
     * @return string
     */
    protected function _quote($value) {
        if(stristr($value, $this->_quoteSign)) {
            $value = addslashes($value);
        }
        
        return $this->_quoteSign.$value.$this->_quoteSign;
    }
    
    /**
     * 
     * @param array|null $filters
     * @return string
     */
    public function toSQL($filters = null) {
        if (empty($filters)) {
            $filters = $this->_parsedData;
        }
        
        $sqls = array();
        
        foreach($filters as $filter) {
            if(isset($filter['sql'])) {
                $sqls[] = $filter['sql'];
            }
        }
        
        return implode($this->_defafaultSqlRuleImploder, $sqls);
    }

}
