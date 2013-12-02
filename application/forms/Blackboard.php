<?php

class Application_Form_Blackboard extends Core_Form {

    public function init() {
        $this->initFormSettings();

        //add form elements
        $this->addTitle()
                ->addDateFrom()
                ->addDateTo()
                ->addActive()
                ->addOrdering()
                ->addText()
                ->addSubmit();
    }

    /**
     *
     * @return \Application_Form_Blackboard
     */
    public function setFilterableForm() {
        $this->removeElements(array(
            'date_from',
            'date_to',
            'text',
            'ordering'
        ));

        return $this;
    }

}

