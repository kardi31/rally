<?php

class Bid extends Form{
    public function __construct(){
        $this->createElement('text','bid',array('validators' => array('int')),'Cena');
        $this->getElement('bid')->addParam('autocomplete','off');
        $this->createElement('submit','submit');
    }
}

