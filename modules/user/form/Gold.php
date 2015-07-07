<?php

class Gold extends Form{
    public function __construct(){
        $gold = $this->createElement('select','gold',array(),'Gold');
        $gold->addMultiOptions(array(
            7 => '7 days',
            30 => '30 days',
            90 => '90 days',
            180 => '180 days'
            
            ));
        $gold->addClass('form-control');
        $submit = $this->createElement('submit','submit',array(),'Submit request');
        $submit->addClass('btn btn-info');
    }
}

