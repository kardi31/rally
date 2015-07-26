<?php

class Login extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username',array());
        $username->addClass('textInput');
//        $username->setAttrib('required');
        $password = $this->createElement('password','password',array(),'Hasło');
        $password->addClass('textInput');
//        $password->setAttrib('required');
        
        $this->createElement('submit','submit');
    }
}

