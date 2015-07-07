<?php

class Premium extends Form{
    public function __construct(){
        $premium = $this->createElement('number','premium',array('validators' => array('int')),'Premium');
        $premium->addParam('placeholder','Enter requested amount');
        $premium->setValue(100);
        $submit = $this->createElement('submit','submit',array(),'Submit request');
        $submit->addClass('btn btn-info');
    }
}

