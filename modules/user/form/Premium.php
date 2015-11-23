<?php

class Premium extends Form{
    public function __construct(){
        $premium = $this->createElement('radio','premium');
        $premium->addMultiOptions(array(
            100 => '100 '.View::getInstance()->translate('premium points'),
            315 => '315 '.View::getInstance()->translate('premium points'),
            550 => '550 '.View::getInstance()->translate('premium points'),
            1150 => '1150 '.View::getInstance()->translate('premium points'),
        ));
//        $premium->addParam('required');
        $premium->addValidator('notEmpty');
        $provider = $this->createElement('radio','provider');
        $provider->addMultiOptions(array(
            'paypal' => '<img src="https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" />',
            'transferuj' => '<img src="/images/layout/transferuj_logo.jpg" />',
        ));
//        $provider->addParam('required');
        $provider->addValidator('notEmpty');
//        $premium->addParam('placeholder','Enter requested amount');
//        $premium->setValue(100);
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Buy premium'));
        $submit->addClass('btn myBtn');
    }
}

