<?php

class Post extends Form{
    public function __construct(){
//        $active = $this->createElement('hidden','active',array());
        $content = $this->createElement('textarea','content',array(),'Opis');
        $content->addParam('rows',4);
        $content->addParam('cols',80);
//        $content->addValidator('alnum');
        $content->addFilter('specialchars');
        $content->addFilter('trim');
        $content->addClass('form-control');
        $content->addParam('required','required');
        $content->addValidator('stringLength',array('min' => 4));
        $moderator_notes = $this->createElement('textarea','moderator_notes',array());
        $moderator_notes->addParam('rows',4);
        $moderator_notes->addParam('cols',80);
        $active = $this->createElement('checkbox','active',array());
        $submit = $this->createElement('submit','submit',array(),View::getInstance()->translate('Submit reply'));
        $submit->addClass('btn myBtn');
    }
}

