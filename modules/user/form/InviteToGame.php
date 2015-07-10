<?php

class InviteToGame extends Form{
    public function __construct(){
        $email = $this->createElement('text','email',array(),'Email');
        $email->addParam('placeholder','Enter user email address');
        $email->addClass('form-control');
        $submit = $this->createElement('submit','submit',array(),'Send invitation');
        $submit->addClass('btn btn-primary');
    }
}

