<?php

class NameChange extends Form{
    public function __construct(){
        
        $name = $this->createElement('text','name',array('validators' => array('alnum')),'Nowa nazwa(dozwolona 1 zmiana na miesiąc)');
        $name->addClass('form-control');
        $name->addValidator('alnum');
        $name->addValidator('stringLength',array('min' => 4,'max' => 12));
        $submit = $this->createElement('submit','submit',array(),'Change');
        $submit->addClass('btn btn-primary');
    }
}

