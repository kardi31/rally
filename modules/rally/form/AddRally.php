<?php

class AddRally extends Form{
    public function __construct(){
//        $this->addClass('form-horizontal');
        $name = $this->createElement('text','name',array(),'Nazwa');
        $name->addAdminDefaultClasses();
        $date = $this->createElement('text','date',array('calendar' => true),'Data');
        $date->addAdminDefaultClasses();
        $date->addParam('readonly', '');
        $date->addParam('size', '16');
        $min_time = $this->createElement('text','min_time',array(),'Minimalny czas');
        $min_time->addAdminDefaultClasses();
        $min_time->addClass('timePicker');
        $surface1 = $this->createElement('select','surface1',array(),'Nawierzchnia 1');
        $surface1->addClass('form-control');
        $surface2 = $this->createElement('select','surface2',array(),'Nawierzchnia 2');
        $surface2->addClass('form-control');
        $surface3 = $this->createElement('select','surface3',array(),'Nawierzchnia 3');
        $surface3->addClass('form-control');
        $percent1 = $this->createElement('text','percent1',array());
        $percent1->addAdminDefaultClasses();
        $percent1->addClass('input-xsmall');
        $percent2 = $this->createElement('text','percent2',array());
        $percent2->addAdminDefaultClasses();
        $percent2->addClass('input-xsmall');
        $percent3 = $this->createElement('text','percent3',array());
        $percent3->addAdminDefaultClasses();
        $percent3->addClass('input-xsmall');

        $active = $this->createElement('checkbox','active',array());
        $active->addAdminDefaultClasses();
        $active->addParam('checked', 'checked');
        
    }
}
