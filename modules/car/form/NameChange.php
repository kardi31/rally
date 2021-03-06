<?php

class NameChange extends Form{
    public function __construct(){
        
        $name = $this->createElement('text','name',array('validators' => array('alphanum')),'Nowa nazwa(dozwolona 1 zmiana na miesiąc)');
        $name->addClass('form-control');
        $name->addFilter('trim');
        $name->addValidator('stringLength',array('min' => 4,'max' => 30));
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Change'));
        $submit->addClass('btn btn-primary');
    }
}

