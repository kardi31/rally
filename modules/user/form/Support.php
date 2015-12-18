<?php

class Support extends Form{
    public function __construct(){
        $supportService = Controller::getService('user','support');
        
        $section = $this->createElement('select','section');
        $section->addMultiOptions($supportService->getSupportCategories());
        $section->addClass('form-control');
        
        $content = $this->createElement('textarea','content');
        $content->addClass('form-control');
        $content->addFilter('trim');
        $content->addParam('required');
        $content->addValidator('letterLength',array('min' => 3));
        $content->addValidator('stringLength',array('min' => 3));
        
        
        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn myBtn');
        $submit->setLabel(View::getInstance()->translate('Send'));
    }
}

