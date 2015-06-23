<?php

class NameChange extends Form{
    public function __construct(){
        
        $name = $this->createElement('text','name',array('validators' => 'alnum'),'Nowa nazwa(dozwolona 1 zmiana na miesiąc)');
        $name->addClass('form-control');
        $submit = $this->createElement('submit','submit',array(),'Change');
        $submit->addClass('btn btn-primary');
    }
}

