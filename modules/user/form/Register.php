<?php

class Register extends Form{
    public function __construct(){
        
        $this->createElement('text','login',array('validators' => array('stringLength' => array('min' => 4,'max' => 12)),'Login'));
        $this->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $this->createElement('password','password2',array('validators' => array('match' => array('elem' => 'password'))),'Powtórz hasło');
        $email = $this->createElement('text','email',array('validators' => 'email'),'Adres email');
        $email->addClass('textInput');
        $this->createElement('captcha','captcha',array());
        $this->createElement('submit','submit');
    }
}

