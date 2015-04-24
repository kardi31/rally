<?php

class InviteFriendly extends Form{
    public function __construct(){
        $name = $this->createElement('text','name',array(),'Nazwa');
        $name->addParam('required');
//        $name->addValidator('alnum');
        
        
        $submit = $this->createElement('submit','submit',array(),'Submit');
    }
}

