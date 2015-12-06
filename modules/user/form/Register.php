<?php

class Register extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username',array('validators' => array('stringLength' => array('min' => 4,'max' => 12)),'Login'));
        $username->addClass('textInput textInput2');
        $username->addFilter('trim');
        $username->addParam('required');
        $username->addFilter('lower');
        $username->addValidator('letterLength',array('min' => 3));
        $password = $this->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $password->addClass('textInput textInput2');
        $password->addParam('required');
        $password2 = $this->createElement('password','password2',array('validators' => array('match' => array('elem' => 'password'))),'Powtórz hasło');
        $password2->addClass('textInput textInput2');
        $password2->addParam('required');
        $email = $this->createElement('text','email',array('validators' => array('email')),'Adres email');
        $email->addClass('textInput textInput2');
        $email->addFilter('trim');
        $email->addFilter('lower');
        $this->createElement('hidden','invite');
//        $this->createElement('captcha','captcha',array());
        $this->createElement('submit','submit');
    }
}

