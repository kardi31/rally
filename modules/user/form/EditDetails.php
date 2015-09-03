<?php

class EditDetails extends Form{
    public function __construct(){
        
        $firstname = $this->createElement('text','first_name',array('validators' => array('stringLength' => array('min' => 3,'max' => 12))),'Imie');
        $lastname = $this->createElement('text','last_name',array('validators' => array('stringLength' => array('min' => 3,'max' => 12))),'Nazwisko');

        $anonymous = $this->createElement('checkbox','anonymous');
        
        $country = $this->createElement('select','country');

        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn myBtn');
        $submit->setLabel('Change details');
    }
}

