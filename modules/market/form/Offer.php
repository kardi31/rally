<?php

class Offer extends Form{
    public function __construct(){
        $this->createElement('text','asking_price',array('validators' => array('int')),'Cena');
        $this->createElement('text','selling_fee',array('validators' => array('int')),'Cena');
        $this->getElement('selling_fee')->addParam('readonly','readonly');
        $this->getElement('asking_price')->addParam('autocomplete','off');
        $days = $this->createElement('select','days',array(),'Test');
        $days->addMultiOptions(array(1,2,3));
        $this->createElement('submit','submit');
    }
}

