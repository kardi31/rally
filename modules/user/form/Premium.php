<?php

class Premium extends Form{
    public function __construct(){
        $premium = $this->createElement('radio','premium');
        $premium->addMultiOptions(array(
            100 => '100 premium points',
            315 => '315 premium points',
            550 => '550 premium points',
            1150 => '1150 premium points',
        ));
        $provider = $this->createElement('radio','provider');
        $provider->addMultiOptions(array(
            'paypal' => '<img src="https://www.sandbox.paypal.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" />',
            'transferuj' => '<img src="/images/layout/transferuj_logo.jpg" />',
        ));
//        $premium->addParam('placeholder','Enter requested amount');
//        $premium->setValue(100);
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Submit request'));
        $submit->addClass('btn myBtn');
    }
}

