<?php

class Post extends Form{
    public function __construct(){
//        $active = $this->createElement('hidden','active',array());
        $content = $this->createElement('textarea','content',array(),'Opis');
        $content->addParam('rows',4);
        $content->addParam('cols',80);
        $submit = $this->createElement('submit','submit',array(),'Submit');
    }
}

