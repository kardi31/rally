<?php

class Bid extends Form{
    public function __construct(){
         
        $view = View::getInstance();
        
        $this->createElement('number','bid',array('validators' => array('int')),'Cena');
        $this->getElement('bid')->addParam('autocomplete','off');
        $this->createElement('hidden','offer_id');
        $submit = $this->createElement('submit','submit',array(),$view->translate('Submit bid'));
        $submit->addClass('btn btn-info');
    }
}

