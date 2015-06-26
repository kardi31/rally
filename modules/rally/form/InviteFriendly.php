<?php

class InviteFriendly extends Form{
    public function __construct(){
        $name = $this->createElement('text','name',array(),'Nazwa');
        $name->addParam('required');
        $name->addClass('form-control');
        $name->addParam('placeholder',"Enter friend name");
        $name->addParam('required');
        
        
        $submit = $this->createElement('submit','submit',array(),'Invite');
        $submit->addClass('btn btn-primary');
    }
}

