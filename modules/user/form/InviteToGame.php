<?php

class InviteToGame extends Form{
    public function __construct(){
        $view = View::getInstance();
        $email = $this->createElement('text','email',array(),'Email');
        $email->addParam('placeholder',$view->translate('Enter user email address'));
        $email->addClass('form-control');
        $submit = $this->createElement('submit','submit',array(),$view->translate('Send invitation'));
        $submit->addClass('btn myBtn');
    }
}

