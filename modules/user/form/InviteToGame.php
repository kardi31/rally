<?php

class InviteToGame extends Form{
    public function __construct(){
        $email = $this->createElement('text','email',array(),'Email');
        $email->addParam('placeholder','Enter user email address');
        $this->createElement('submit','submit');
    }
}

