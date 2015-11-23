<?php

class SearchUser extends Form{
    public function __construct(){
        
        $username = $this->createElement('text','username');
        $username->addClass('form-control');
        $username->addFilter('trim');
        $username->addParam('placeholder',View::getInstance()->translate('Enter user name'));
        $username->addParam('id','searchUsername');
        $username->addParam('required');
        $username->addFilter('lower');
        $username->addValidator('letterLength',array('min' => 3));
        
        
        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn myBtn searchUsersBtn');
        $submit->setLabel(View::getInstance()->translate('Search'));
    }
}

