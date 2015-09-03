<?php

class ChangeEmail extends Form{
    public function __construct(){
        
        $password = $this->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');

        $newEmail = $this->createElement('text','newemail',array('validators' => 'email'),'Adres email');

        $newEmail->addFilter('trim');
        $newEmail->addParam('autocomplete','off');
        $newEmail->addFilter('lower');
        $oldEmail = $this->createElement('text','oldemail',array('validators' => 'email'),'Adres email');

        $oldEmail->addFilter('trim');
        $oldEmail->addFilter('lower');
        $oldEmail->addParam('autocomplete','off');
        $oldEmail->addParam('value','');
        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn myBtn');
        $submit->setLabel('Change email');
    }
}

