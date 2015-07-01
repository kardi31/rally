<?php

class BidPlayer extends Form{
    public function __construct(){
         
        $this->createElement('number','bid',array('validators' => array('int')),'Cena');
        $this->getElement('bid')->addParam('autocomplete','off');
        $this->createElement('hidden','offer_id');
        $submit = $this->createElement('submit','submit',array(),'Submit bid');
        $submit->addClass('btn btn-info');
    }
}

