<?php

class Application_Form_Statistics extends Aktiv_User_Form {

    public function init() {
        $this->initFormSettings();

        $this->addDateFrom(true)
            ->addDateTo(true)
            ->addSubmit(true, "GENERATE");
    }

}

