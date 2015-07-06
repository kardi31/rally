<?php

class Thread extends Form{
    public function __construct(){
        $title = $this->createElement('text','title',array(),'Title');
        $title->addParam('placeholder','Enter title');
        $content = $this->createElement('textarea','content',array(),'Opis');
        $content->addParam('rows',4);
        $content->addParam('cols',80);
        $content->addParam('placeholder','Thread content');
        $moderator_notes = $this->createElement('textarea','moderator_notes',array());
        $moderator_notes->addParam('rows',4);
        $moderator_notes->addParam('cols',80);
        $pinned = $this->createElement('checkbox','pinned',array());
        $active = $this->createElement('checkbox','active',array());
        
        $submit = $this->createElement('submit','submit',array(),'Add thread');
        $submit->addClass('btn btn-info');
        
    }
}

