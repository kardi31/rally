<?php

class Tactics extends Form{
    public function __construct(){
        $this->addClass('form-horizontal');
        
        
        $driver1_id = $this->createElement('select','driver1_id',array(),'Kierowca 1');
        $driver2_id = $this->createElement('select','driver2_id',array(),'Kierowca 2');
        $pilot1_id = $this->createElement('select','pilot1_id',array(),'Pilot 1');
        $pilot2_id = $this->createElement('select','pilot2_id',array(),'Pilot 2');
        $car1_id = $this->createElement('select','car1_id',array(),'Auto 1');
        $car2_id = $this->createElement('select','car2_id',array(),'Auto 2');
        
        
        $submit = $this->createElement('submit','submit',array(),'Submit');
    }
}

