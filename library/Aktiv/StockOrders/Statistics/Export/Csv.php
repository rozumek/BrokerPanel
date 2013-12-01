<?php

class Aktiv_StockOrders_Statistics_Export_Csv extends Core_Export_Csv {

    /**
     *
     * @var array
     */
    protected $_allowedModes = array(
        'week',
        'month',
        'custom'
    );

    /**
     *
     * @var string
     */
    protected $_mode = 'week';

    /**
     *
     * @var array
     */
    protected $_headers = array(
        'Date/Broker'
    );

    /**
     *
     * @var string
     */
    protected $_dateFrom = null;

    /**
     *
     * @var string
     */
    protected $_dateTo = null;

    /**
     *
     * @param string $date
     * @return \Aktiv_StockOrders_Statistics_Export_Csv
     */
    public function setDateFrom($date) {
        $this->_dateFrom = $date;
        return $this;
    }

    /**
     *
     * @param string $date
     * @return \Aktiv_StockOrders_Statistics_Export_Csv
     */
    public function setDateTo($date) {
        $this->_dateTo = $date;
        return $this;
    }

    /**
     *
     * @param string $mode
     * @return \Aktiv_StockOrders_Statistics_Export_Csv
     */
    public function setMode($mode) {
        if (in_array($mode, $this->_allowedModes)) {
            $this->_mode = $mode;
        }

        return $this;
    }

    /**
     *
     * @return \Aktiv_StockOrders_Statistics_Export_Csv
     */
    public function export() {
        $filename = $this->getFullFilename();

        if (false === realpath($filename)) {
            touch($filename);
            chmod($filename, 0777);
        }

        $handle = fopen(realpath($filename), "w");

        $data = $this->processData($this->getData());

        //add headers
        $this->_headers = array_merge($this->_headers, array_keys($data));

        $column = 1;
        $processRows = $this->_prepareExportData(count(array_keys($data)));

        foreach ($data as $broker) {
            $rowIt = 1;
            $row = 1;
            foreach ($broker as $date => $fee) {
                if ($rowIt == $row) {
                    $processRows[$date][0] = $date;
                    $processRows[$date][$column] = $fee;
                    $row++;
                    //continue;
                }
                $rowIt++;
            }
            $column++;
        }

        foreach ($processRows as $row) {
            fputcsv($handle, $this->_fixEncoding($row), ";");
        }

        fclose($handle);

        return $this;
    }

    /**
     *
     * @return array
     */
    protected function _prepareExportData($count) {
        $processRows = array();
        $processRows[0] = $this->_headers;

        $startDay = null;
        $endDay = null;
        $timestamp = time();

        if ($this->_mode == 'week') {
            $startDay = date("Y-m-d", strtotime("last monday", $timestamp));
            $endDay = date("Y-m-d", strtotime("next monday", $timestamp));
        } else if ($this->_mode == 'month') {
            $startDay = date("Y-m-01");
            $endDay = date("Y-m-t");
        } else if($this->_mode == 'custom') {
            $startDay = $this->_dateFrom;
            $endDay = $this->_dateTo;
        }

        if ($startDay !== null && $endDay !== null) {
            $dateString = $startDay;

            while ($dateString != $endDay) {
                $processRows[$dateString] = array();

                for ($i = 0; $i < $count; $i++) {
                    if ($i == 0) {
                        $processRows[$dateString][$i] = $dateString;
                    } else {
                        $processRows[$dateString][$i] = 0.0;
                    }
                }

                $date = date_create_from_format('Y-m-d', $dateString);
                $dateString = date_add($date, date_interval_create_from_date_string('1 day'))->format('Y-m-d');
            }
        }

        return $processRows;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function processData(array $data) {
        $processedData = array();

        foreach ($data as $stat) {
            if (!isset($processedData[$stat['broker_name']])) {
                $processedData[$stat['broker_name']] = array();
            }

            $processedData[$stat['broker_name']][$stat['date']] = $stat['fee_income'];
        }

        return $processedData;
    }

}
