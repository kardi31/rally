<?php

class Sponsor extends Form{
    public function __construct(){
        $this->addClass('form-horizontal');
        $name = $this->createElement('text','name',array(),'Nazwa');
	$name->addAdminDefaultClasses();
        $length = $this->createElement('text','length',array(),'Długość');
	$length->addAdminDefaultClasses();
        $min_time = $this->createElement('select','surface1',array(),'Minimalny czas');
	$min_time->addAdminDefaultClasses();
    }
}

