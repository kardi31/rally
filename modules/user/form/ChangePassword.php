<?php

class ChangePassword extends Form{
    public function __construct(){
        
//        $username = $this->createElement('text','username',array('validators' => array('stringLength' => array('min' => 4,'max' => 12)),'Login'));
//        $username->addClass('textInput');
//        $username->addFilter('trim');
//        $username->addFilter('lower');
//        $username->addValidator('letterLength',array('min' => 3));
        $passwordOld = $this->createElement('password','oldpw',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Stare hasło');
        $passwordOld->addParam('autocomplete','off');
//        $passwordOld->addClass('textInput');
        $password = $this->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
//        $password->addClass('textInput');
        $password2 = $this->createElement('password','password2',array('validators' => array('match' => array('elem' => 'password'))),'Powtórz hasło');
//        $password2->addClass('textInput');
       
        $submit = $this->createElement('submit','submit',array(),'Submit');
        $submit->addClass('btn myBtn');
        $submit->setLabel(View::getInstance()->translate('Change password'));
    }
}

