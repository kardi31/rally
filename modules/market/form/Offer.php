<?php

class Offer extends Form{
    public function __construct(){
        $view = View::getInstance();
        $askingPrice = $this->createElement('text','asking_price',array('validators' => array('int')),'Cena');
        $askingPrice->addClass('form-control');
        $askingPrice->addParam('required');
        $fee = $this->createElement('text','selling_fee',array('validators' => array('int')),'Cena');
        $fee->addParam('readonly','readonly');
        $fee->addClass('form-control');
        $fee->addParam('required');
        $this->getElement('asking_price')->addParam('autocomplete','off');
        $days = $this->createElement('select','days',array(),'Test');
        $days->addMultiOptions(array(1 => 1,2 => 2,3 => 3));
        $days->addClass('form-control');
        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn btn-success');
        $submit->setLabel($view->translate('Put on market'));
    }
}

