<?php

class Login extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username',array());
        $username->addClass('textInput');
//        $username->setAttrib('required');
        $password = $this->createElement('password','password',array(),'Hasło');
        $password->addClass('textInput');
//        $password->setAttrib('required');
        if(isset($_SESSION['wrong_pword'])&&(int)$_SESSION['wrong_pword']>=3){
            $this->createElement('recaptcha','recaptcha');
        }
        
        $this->createElement('submit','submit');
    }
}

