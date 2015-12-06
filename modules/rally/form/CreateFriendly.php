<?php

class CreateFriendly extends Form{
    public function __construct(){
        $name = $this->createElement('text','name',array(),'Nazwa');
        $name->addParam('required');
        $name->addValidator('alphanum');
        $name->addValidator('stringLength',array('min' => 5));
        $name->addClass('form-control');
        $name->addFilter('trim');
        
        $description = $this->createElement('textarea','description',array(),'Opis');
        $description->addValidator('alphanum');
        $description->addParam('rows',4);
        $description->addParam('cols',70);
//        $description->addParam('required');
        $description->addFilter('trim');
//        $description->addValidator('stringLength',array('min' => 5));
        $description->addClass('form-control');
        
        $date = $this->createElement('text','date',array(),'Data');
        $date->addParam('required');
        $date->addParam('readonly');
        $date->addParam('class','form_advance_datetime');
        $date->addValidator('notEmpty');
        $date->setNoPopulate();
        
        $invite_only = $this->createElement('radio','invite_only',array(),'Tylko dla przyjaciół');
        $invite_only->addMultiOption(1,'Private');
        $invite_only->addMultiOption(0,'Public');
        $invite_only->addParam('required');
        
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Submit'));
        $submit->addClass('btn myBtn');
        
    }
}

