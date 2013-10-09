<?php

abstract class Core_Model_Db_Table_Abstract extends Zend_Db_Table_Abstract implements Core_Model_Interface{

    /**
     *
     * @var bool
     */
    protected static $_cacheEnable = false;

    /**
     *
     * @var Zend_Cache_Core
     */
    protected static $_cache = null;

    /**
     *
     * @var string
     */
    protected $_lastMessage = null;

    /**
     *
     * @var Zend_Exception
     */
    protected $_lastException = null;

    /**
     * Classname for row
     *
     * @var string
     */
    protected $_rowClass = 'Core_Model_Db_Table_Row';

    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = 'Core_Model_Db_Table_Rowset';

    /**
     *
     * @var Core_Parser_SqlFilters
     */
    protected $_sqlFiltersParser;

    /**
     *
     * @param array $config
     */
    public function __construct($config = array()) {
        $this->_sqlFiltersParser = new Core_Parser_SqlFilters();
        parent::__construct($config);
    }

    /**
     *
     * @return Core_Parser_SqlFilters
     */
    public function getSqlFiltersParser() {
        return $this->_sqlFiltersParser;
    }


    /**
     *
     * @return string
     */
    public function getTableName() {
        return $this->_name;
    }

    /**
     *
     * @return string
     */
    public function getLastMessage(){
        return $this->_lastMessage;
    }

    /**
     *
     * @return Zend_Exception
     */
    public function getLastException(){
        return $this->_lastException;
    }


    /**
     *
     * @param array $filters
     * @param string $sort
     * @param int|null $limit
     * @param int $offset
     */
    public function fetchItems($filters=array(), $sort=null, $limit=null, $offset=null){
        if((int)$limit < 0) {
            $limit = null;
        }

        if((int)$offset < 0) {
            $offset = null;
        }

        return $this->fetchAll($this->_buildQuery($filters, $sort, $limit, $offset));
    }

    /**
     *
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @throws Core_Exception
     * @return Zend_Db_Table_Select
     */
    protected function _buildQuery($filters=array(), $sort=null, $limit=null, $offset=null){
         $query = $this->select(true)
                ->setIntegrityCheck(false)
                ->limit($limit, $offset)
                ;

        $parser = $this->getSqlFiltersParser();

        if($parser->isParsable($filters)){
            $parser->parseFilters($filters);
            $query->where($parser->toSQL());
        }

        if(!empty($sort)){
            $query->order($sort);
        }

        return $query;
    }

    /**
     *
     * @param array $filters
     * @param string $sort
     * @param int $limit
     * @param int $offset
     * @throws Core_Exception
     * @return string
     */
    public function getQuery($filters=array(), $sort=null, $limit=null, $offset=null){
        return $this->_buildQuery($filters, $sort, $limit, $offset);
    }

        /**
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $count  OPTIONAL An SQL LIMIT count.
     * @param int                               $offset OPTIONAL An SQL LIMIT offset.
     * @return Core_Model_Db_Table_Rowset_Abstract The row results per the Zend_Db_Adapter fetch mode.
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null) {
        if(self::isCacheEnabled()){
            $cacheId = 'list_'.$this->_prepareCacheIdSufix($where, $order, $count, $offset);
        }

        $data = null;
        if(!self::isCacheEnabled() || ($data = self::getCacheManager()->load($cacheId)) === false){
            if (!($where instanceof Zend_Db_Table_Select)) {
                $select = $this->select();

                if ($where !== null) {
                    $this->_where($select, $where);
                }
                if ($order !== null) {
                    $this->_order($select, $order);
                }
                if ($count !== null || $offset !== null) {
                    $select->limit($count, $offset);
                }
            } else {
                $select = $where;
            }

            $rows = $this->_fetch($select);
            $data  = array(
                'table'    => $this,
                'data'     => $rows,
                'readOnly' => $select->isReadOnly(),
                'rowClass' => $this->getRowClass(),
                'stored'   => true
            );

            if(self::isCacheEnabled()){
                self::getCacheManager()->save($data);
            }
        }

        $rowsetClass = $this->getRowsetClass();
        if (!class_exists($rowsetClass)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($rowsetClass);
        }

        return new $rowsetClass($data);
    }

    /**
     *
     * @param string|array|Zend_Db_Table_Select $where  OPTIONAL An SQL WHERE clause or Zend_Db_Table_Select object.
     * @param string|array                      $order  OPTIONAL An SQL ORDER clause.
     * @param int                               $offset OPTIONAL An SQL OFFSET value.
     * @return Core_Model_Db_Table_Row_Abstract|null The row results per the
     *     Zend_Db_Adapter fetch mode, or null if no row found.
     */
    public function fetchRow($where = null, $order = null, $offset = null) {
        if(self::isCacheEnabled()){
            $cacheId = 'row_'.$this->_prepareCacheIdSufix($where, $order, $offset);
        }

        $cachedData = null;
        if(!self::isCacheEnabled() || ($cachedData = self::getCacheManager()->load($cacheId)) === false){
            if (!($where instanceof Zend_Db_Table_Select)) {
                $select = $this->select();

                if ($where !== null) {
                    $this->_where($select, $where);
                }

                if ($order !== null) {
                    $this->_order($select, $order);
                }

                $select->limit(1, ((is_numeric($offset)) ? (int) $offset : null));

            } else {
                $select = $where->limit(1, $where->getPart(Zend_Db_Select::LIMIT_OFFSET));
            }

            $rows = $this->_fetch($select);

            if (count($rows) == 0) {
                return null;
            }

            $data = array(
                'table'   => $this,
                'data'     => $rows[0],
                'readOnly' => $select->isReadOnly(),
                'stored'  => true
            );

            $cachedData = array(
                'data' => $data,
            );

            if(self::isCacheEnabled()){
                self::getCacheManager()->save($cachedData);
            }
        }else{
            $data = $cachedData['data'];
        }

        $rowClass = $this->getRowClass();
        if (!class_exists($rowClass)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($rowClass);
        }

        return new $rowClass($data);
    }

    /**
     *
     * @param Zend_Cache_Core $cache
     */
    public function setCacheManager(Zend_Cache_Core $cache){
        self::$_cache = $cache;
    }

    /**
     *
     * @return Zend_Cache_Core
     */
    public static function getCacheManager(){
        return self::$_cache;
    }

    /**
     *
     * @param bool $state
     */
    public static function enableCaching($state){
        self::$_cacheEnable = (bool)$state;
    }

    /**
     *
     * @return bool
     */
    public static function isCacheEnabled(){
        return (self::$_cacheEnable === true && self::$_cache instanceof Zend_Cache_Core);
    }

    /**
     *
     * @return string
     */
    private function _prepareCacheIdSufix(){
        $args = func_get_args();
        $args[0] = (string)$args[0];
        $filter = new Zend_Filter_Alnum;
        $sufix = $filter->filter(md5(base64_encode(serialize($args))));

        return $this->_name.'_'.$sufix;
    }
}
