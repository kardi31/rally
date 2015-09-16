<?php

class Gold extends Form{
    public function __construct(){
        $view = View::getInstance();
        $gold = $this->createElement('radio','gold',array(),'Gold');
        $gold->addMultiOptions(array(
            30 => '30 '.$view->translate('days').' - 80',
            90 => '90 '.$view->translate('days').' - 230',
            180 => '180 '.$view->translate('days').' - 430',
            360 => '360 '.$view->translate('days').' - 760'
            
            ));
        $gold->addClass('form-control');
        $submit = $this->createElement('submit','submit',array(),$view->translate('Buy package'));
        $submit->addClass('btn myBtn');
    }
}

