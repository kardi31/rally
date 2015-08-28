<?php

class Thread extends Form{
    public function __construct(){
        $view = View::getInstance();
        $title = $this->createElement('text','title',array(),'Title');
        $title->addParam('placeholder',$view->translate('Enter title'));
        $title->addValidator('alnum');
        $title->addFilter('trim');
        $title->addParam('required','required');
        $title->addValidator('stringLength',array('min' => 4,'max' => 20));
        $content = $this->createElement('textarea','content',array(),'Opis');
        $content->addParam('rows',4);
        $content->addParam('cols',80);
        $content->addParam('placeholder',$view->translate('Thread content'));
        $content->addValidator('alnum');
        $content->addParam('required','required');
        $content->addFilter('trim');
        $content->addValidator('stringLength',array('min' => 4));
        $moderator_notes = $this->createElement('textarea','moderator_notes',array());
        $moderator_notes->addParam('rows',4);
        $moderator_notes->addParam('cols',80);
        $pinned = $this->createElement('checkbox','pinned',array());
        $active = $this->createElement('checkbox','active',array());
        
        $submit = $this->createElement('submit','submit',array(),$view->translate('Add thread'));
        $submit->addClass('btn myBtn');
        
    }
}

