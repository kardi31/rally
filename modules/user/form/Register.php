<?php

class Register extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username',array('validators' => array('stringLength' => array('min' => 4,'max' => 12)),'Login'));
        $username->addClass('textInput');
        $password = $this->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $password->addClass('textInput');
        $password2 = $this->createElement('password','password2',array('validators' => array('match' => array('elem' => 'password'))),'Powtórz hasło');
        $password2->addClass('textInput');
        $email = $this->createElement('text','email',array('validators' => 'email'),'Adres email');
        $email->addClass('textInput');
        $this->createElement('captcha','captcha',array());
        $this->createElement('submit','submit');
    }
}

