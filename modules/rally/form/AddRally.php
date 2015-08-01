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
        $min_time = $this->createElement('textarea','description',array(),'Minimalny czas');
        $min_time->addAdminDefaultClasses();
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

        $award1 = $this->createElement('select','award1',array(),'Nagroda 1');
        $award1->addClass('form-control');
        $award2 = $this->createElement('select','award2',array(),'Nagroda 2');
        $award2->addClass('form-control');
        $award3 = $this->createElement('select','award3',array(),'Nagroda 3');
        $award3->addClass('form-control');
        
        $award_premium1 = $this->createElement('text','award_premium1',array(),'Nagroda 1');
        $award_premium1->addClass('form-control');
        $award_premium1->addParam('placeholder','Premium');
        $award_premium2 = $this->createElement('text','award_premium2',array(),'Nagroda 2');
        $award_premium2->addClass('form-control');
        $award_premium2->addParam('placeholder','Premium');
        $award_premium3 = $this->createElement('text','award_premium3',array(),'Nagroda 3');
        $award_premium3->addClass('form-control');
        $award_premium3->addParam('placeholder','Premium');
        
        $active = $this->createElement('checkbox','active',array());
        $active->addAdminDefaultClasses();
        $active->addParam('checked', 'checked');
        
    }
}
