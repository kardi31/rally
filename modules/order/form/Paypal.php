<?php

class Paypal extends Form{
    public function __construct(){
        $this->createElement('hidden','cmd');
        $this->createElement('hidden','hosted_button_id');
        $this->createElement('hidden','on0');
        $this->createElement('hidden','os0');
        $this->createElement('hidden','currency_code');
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Submit request'));
        $submit->addClass('btn myBtn');
    }
}

