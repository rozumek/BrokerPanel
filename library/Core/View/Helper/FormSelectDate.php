<?php

class Core_View_Helper_FormSelectDate extends Core_View_Helper_Abstract
{
    /**
     *
     * @var string
     */
    protected $_yearStart = 2012;

    /**
     *
     * @var string
     */
    protected $_yearStop = null;

    /**
     *
     * @var bool
     */
    protected static $_scriptAdded = false;

    /**
     *
     * @param int $value
     * @param int $yearStart
     * @param int $yearStop
     * @param type $attribs
     */
    public function formSelectDate($name, $value = '', $attribs = array())
    {
        if($value == '0000-00-00 00:00:00') {
            $value = '';
        }

        $format = (!empty($attribs['format']))?$attribs['format']:'Y-m-d';

        if(!empty($attribs['yearStart']) && !empty($attribs['yearStop'])){
            if($attribs['yearStart'] > 0 && $attribs['yearStop'] > $attribs['yearStart']){
                $this->_yearStart = $attribs['yearStart'];
                $this->_yearStop = $attribs['yearStop'];
            }
        }else if(!empty($attribs['yearStart'])){
            $this->_yearStart = $attribs['yearStart'];
        }

        $id = (!empty($attribs['id']))?' id="'.$attribs['id'].'"':'';

        if(empty($this->_yearStop)){
            $this->_yearStop = date('Y');
        }

        $day = '';
        $month = '';
        $year = '';

        try{
            $date = DateTime::createFromFormat($format, substr($value, 0, 10));

            if($date instanceof DateTime){
                $day = $date->format('d');
                $month = $date->format('m');
                $year = $date->format('Y');
                $value = $date->format('Y-m-d');
            }
        }catch(Exception $e){

        }

        $dayList = $this->getDays($name, $day);
        $monthList = $this->getMonths($name, $month);
        $yearList = $this->getYears($name, $year);
        $hidden = $this->getHiddenDate($name, $value);

        $this->appendScripts();

        return '<span'.$id.' class="form-date-select">'.$dayList.$monthList.$yearList.$hidden.'</span>';
    }


    public function appendScripts()
    {
        if(self::$_scriptAdded === false){
            self::$_scriptAdded = true;

            $this->getView()
                    ->headScript()
                    ->appendFile($this->_jsHelperPath.'/formSelectDate.js');
        }

        return $this;
    }


    /**
     *
     * @param string $name
     * @param string $value
     * @return string
     */
    public function getHiddenDate($name, $value)
    {
        return $this->_invokeHelper('hidden', $name, $value, $this->_defaultAttribs);
    }

    /**
     *
     * @param string $name
     * @param int $value
     * @return string
     */
    public function getDays($name, $value)
    {
        return $this->_invokeHelper(
            'select',
            null,
            $value,
            array('class' => 'day', 'id' => $name.'_part[day]'),
            $this->_rangeArray(1, 31, 'dd')
        );
    }

    /**
     *
     * @param string $name
     * @param int $value
     * @return string
     */
    public function getMonths($name, $value)
    {
        return $this->_invokeHelper(
            'select',
            null,
            $value,
            array('class' => 'month', 'id' => $name.'_part[month]'),
            $this->_rangeArray(1, 12, 'mm')
        );
    }

    /**
     *
     * @param string $name
     * @param int $value
     * @return string
     */
    public function getYears($name, $value)
    {
        return $this->_invokeHelper(
            'select',
            null,
            $value,
            array('class' => 'year', 'id' => $name.'_part[year]'),
            $this->_rangeArray($this->_yearStop, $this->_yearStart, 'yyyy')
        );
    }


}
