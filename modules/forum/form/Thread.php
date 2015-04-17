<?php

class Thread extends Form{
    public function __construct(){
//        $active = $this->createElement('hidden','id',array());
//        $active->setValue(1);
        $title = $this->createElement('text','title',array(),'Title');
        $title->addParam('placeholder','Enter title');
        $content = $this->createElement('textarea','content',array(),'Opis');
        $content->addParam('rows',4);
        $content->addParam('cols',80);
        
        $submit = $this->createElement('submit','submit',array(),'Submit');
        
    }
}

