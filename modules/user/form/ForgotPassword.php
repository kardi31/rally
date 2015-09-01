<?php

class ForgotPassword extends Form{
    public function __construct(){
        $view = View::getInstance();
        $email = $this->createElement('text','email',array(),'Email');
        $email->addParam('placeholder',$view->translate('Enter your email address'));
        $email->addClass('form-control');
        $username = $this->createElement('text','username',array(),'Username');
        $username->addParam('placeholder',$view->translate('Enter your username'));
        $username->addClass('form-control');
        $this->createElement('recaptcha','recaptcha');
        $submit = $this->createElement('submit','submit',array(),$view->translate('Send invitation'));
        $submit->addClass('btn myBtn');
    }
}

