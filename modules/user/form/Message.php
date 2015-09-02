<?php

class Message extends Form{
    public function __construct(){
//        $active = $this->createElement('hidden','active',array());
        $content = $this->createElement('textarea','content',array(),'Opis');
        $content->addParam('rows',4);
        $content->addParam('cols',80);
        $content->addValidator('stringLength',array('min' => 4));
        $content->addValidator('alnum');
        $content->addFilter('trim');
        $content->addParam('required','required');
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Send message'));
        $submit->addClass('btn myBtn');
    }
}

