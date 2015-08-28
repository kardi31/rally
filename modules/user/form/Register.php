<?php

class Register extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username',array('validators' => array('stringLength' => array('min' => 4,'max' => 12)),'Login'));
        $username->addClass('textInput');
        $username->addFilter('trim');
        $username->addFilter('lower');
        $password = $this->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $password->addClass('textInput');
        $password2 = $this->createElement('password','password2',array('validators' => array('match' => array('elem' => 'password'))),'Powtórz hasło');
        $password2->addClass('textInput');
        $email = $this->createElement('text','email',array('validators' => 'email'),'Adres email');
        $email->addClass('textInput');
        $email->addFilter('trim');
        $email->addFilter('lower');
        $invite = $this->createElement('hidden','invite');
        $this->createElement('captcha','captcha',array());
        $this->createElement('submit','submit');
    }
}

