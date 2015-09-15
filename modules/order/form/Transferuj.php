<?php

class Transferuj extends Form{
    public function __construct(){
        $this->createElement('hidden','md5sum');
        $this->createElement('hidden','id');
        $this->createElement('hidden','crc');
        $this->createElement('hidden','opis');
        $this->createElement('hidden','kwota');
        $this->createElement('hidden','wyn_url');
        $this->createElement('hidden','pow_url');
        $this->createElement('hidden','jezyk');
        $this->createElement('hidden','wyn_url');
        $this->createElement('hidden','pow_url');
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Submit request'));
        $submit->addClass('btn myBtn');
    }
}

