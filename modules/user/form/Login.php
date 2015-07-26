<?php

class Login extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username',array());
        $username->addClass('textInput');
        $password = $this->createElement('password','password',array(),'Hasło');
        $password->addClass('textInput');
        
        $this->createElement('submit','submit');
    }
}

