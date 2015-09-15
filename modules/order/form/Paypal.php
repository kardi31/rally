<?php

class Paypal extends Form{
    public function __construct(){
        $this->createElement('hidden','cmd');
        $this->createElement('hidden','business');
        $this->createElement('hidden','item_name');
        $this->createElement('hidden','amount');
        $this->createElement('hidden','quantity');
        $this->createElement('hidden','currency_code');
        $this->createElement('hidden','rm');
        $this->createElement('hidden','quantity');
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Submit request'));
        $submit->addClass('btn myBtn');
    }
}

