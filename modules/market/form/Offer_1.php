<?php

class Offer extends Form{
    public function __construct(){
         $this->createElement('text','asking_price',array('validators' => array('int')),'Cena')->addClass('form-control');
        $fee = $this->createElement('text','selling_fee',array('validators' => array('int')),'Cena');
        $fee->addParam('readonly','readonly');
        $fee->addClass('form-control');
        $this->getElement('asking_price')->addParam('autocomplete','off');
        $days = $this->createElement('select','days',array(),'Test');
        $days->addMultiOptions(array(1 => 1,2 => 2,3 => 3));
        $days->addClass('form-control');
        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn btn-success');
        $submit->setLabel('Put on market');
    }
}

