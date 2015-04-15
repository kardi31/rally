<?php

class Sponsor extends Form{
    public function __construct(){
        $this->addClass('form-horizontal');
        
        $active = $this->createElement('hidden','id',array());
        $name = $this->createElement('text','name',array(),'Nazwa');
	$name->addAdminDefaultClasses();
        $description = $this->createElement('textarea','description',array(),'Opis');
	$description->addAdminDefaultClasses();
        $description->addParam('rows',4);
        $description->addParam('cols',80);
        
        $active = $this->createElement('hidden','active',array());
    }
}

