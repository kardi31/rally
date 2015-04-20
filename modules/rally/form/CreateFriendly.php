<?php

class CreateFriendly extends Form{
    public function __construct(){
        $name = $this->createElement('text','name',array(),'Nazwa');
        $description = $this->createElement('textarea','description',array(),'Opis');
        $description->addParam('rows',4);
        $description->addParam('cols',80);
        
        $invite_only = $this->createElement('radio','invite_only',array(),'Tylko dla przyjaciół');
        $invite_only->addMultiOptions(array('Tylko dla przyjaciół' => 1));
//        $invite_only->addMultiOptions(array(0,'Dla wszystkich'));
        
        $submit = $this->createElement('submit','submit',array(),'Submit');
    }
}

