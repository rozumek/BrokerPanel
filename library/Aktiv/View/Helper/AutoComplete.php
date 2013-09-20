<?php

class Aktiv_View_Helper_AutoComplete extends ZendX_JQuery_View_Helper_AutoComplete {

    /**
     *
     * @var string
     */
    protected $_labelSufix = 'name';

    /**
     *
     * @var array
     */
    protected $_sourceData = array();

    /**
     *
     * @var bool
     */
    protected $_hiddenIdEnabled = false;

    /**
     * Builds an AutoComplete ready input field.
     *
     * @throws ZendX_JQuery_Exception
     * @param  String $id
     * @param  String $value
     * @param  array $params
     * @param  array $attribs
     * @return String
     */
    public function autoComplete($id, $value = null, array $params = array(), array $attribs = array()) {
        $attribs = $this->_prepareAttributes($id, $value, $attribs);

        if (!isset($params['source'])) {
            if (isset($params['url'])) {
                $params['source'] = $params['url'];
                unset($params['url']);
            } else if (isset($params['data'])) {
                $params['source'] = $params['data'];
                unset($params['data']);
            } else {
                require_once "ZendX/JQuery/Exception.php";
                throw new ZendX_JQuery_Exception(
                "Cannot construct AutoComplete field without specifying 'source' field, " .
                "either an url or an array of elements."
                );
            }
        }

        $this->_sourceData = Core_Array::get($params, 'source', array());
        $this->_hiddenIdEnabled = (bool) Core_Array::get($attribs, 'hiddenIdEnabled', false);

        $paramsJson[] = 'source : ' . ZendX_JQuery::encodeJson($this->_sourceData);
        $paramsJson[] = 'minLength : ' . Core_Array::get($params, 'minLength', 2);


        if ($this->_hiddenIdEnabled) {
            $attribs['id'] .= '-' . $this->_labelSufix;
            $attribs['name'] .= '_' . $this->_labelSufix;

            $paramsJson[] = 'focus : ' . 'function( event, ui ) {
                $( "#' . $id . '" ).val( ui.item.label );
                return false;
            }';

            $paramsJson[] = 'select : ' . 'function(event, ui) {
                $("#' . $attribs['id'] . '") .val( ui.item.label) ;
                $("#' . $id . '").val(ui.item.value);

                return false;
            }';

            unset($attribs['hiddenIdEnabled']);
        }

        $paramsJson = '{' . implode(', ', $paramsJson) . '}';

        $js = sprintf('%s("#%s").autocomplete(%s);', ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(), $attribs['id'], $paramsJson);
        $this->jquery->addOnLoad($js);

        $html = '';

        if ($this->_hiddenIdEnabled) {
            $html = $this->view->formText($attribs['id'], $this->determineHiddenValue($value), $attribs);
            $html .= $this->view->formHidden($id, $value);
        } else {
            $html = $this->view->formText($attribs['id'], $value, $attribs);
        }

        return $html;
    }

    /**
     *
     * @param mixed $value
     * @return mixed
     */
    public function determineHiddenValue($value) {
        if ($this->_hiddenIdEnabled && !empty($value)) {
            foreach ($this->_sourceData as $row) {
                if (trim($value) == trim($row['value'])) {
                    return $row['label'];
                }
            }
        } else {
            return $value;
        }

        return null;
    }

}

