<?php

class Gold extends Form{
    public function __construct(){
        $view = View::getInstance();
        $gold = $this->createElement('select','gold',array(),'Gold');
        $gold->addMultiOptions(array(
            7 => '7 '.$view->translate('days'),
            30 => '30 '.$view->translate('days'),
            90 => '90 '.$view->translate('days'),
            180 => '180 '.$view->translate('days')
            
            ));
        $gold->addClass('form-control');
        $submit = $this->createElement('submit','submit',array(),$view->translate('Submit request'));
        $submit->addClass('btn myBtn');
    }
}

