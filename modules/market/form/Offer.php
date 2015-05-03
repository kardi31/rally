<?php

class Offer extends Form{
    public function __construct(){
        $this->createElement('text','title');
        $this->createElement('textarea','content');
        $visible = $this->createElement('checkbox','visible');
        $visible->addParam('checked');
        $visible->setValue(1);
        $publish_date = $this->createElement('text','publish_date');
        $publish_date->addParam('class','datetimepicker1');
    }
}

