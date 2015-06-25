<?php

class CreateFriendly extends Form{
    public function __construct(){
        $name = $this->createElement('text','name',array(),'Nazwa');
        $name->addParam('required');
        $name->addValidator('alnum');
        $name->addValidator('stringLength',array('min' => 5));
        $name->addClass('form-control');
        $description = $this->createElement('textarea','description',array(),'Opis');
        $description->addValidator('alnum');
        $description->addParam('rows',4);
        $description->addParam('cols',70);
        $description->addClass('form-control');
        
        $date = $this->createElement('text','date',array(),'Data');
        $date->addParam('required');
        $date->addParam('readonly');
        $date->addParam('class','form_advance_datetime');
        
        $invite_only = $this->createElement('radio','invite_only',array(),'Tylko dla przyjaciół');
        $invite_only->addMultiOption(1,'Private');
        $invite_only->addMultiOption(0,'Public');
        $invite_only->addParam('required');
        
        $submit = $this->createElement('submit','submit',array(),'Submit');
        $submit->addClass('btn btn-primary');
        
    }
}

