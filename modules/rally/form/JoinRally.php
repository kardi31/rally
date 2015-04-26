<?php
class JoinRally extends Form{
    public function __construct(){
        $driver_id = $this->createElement('select','driver_id',array(),'Kierowca');
        $pilot_id = $this->createElement('select','pilot_id',array(),'Pilot');
        $car_id = $this->createElement('select','car_id',array(),'Auto');
        $risk = $this->createElement('select','risk',array('selected' => 'Normal risk'),'Ryzyko');
        $risk->addMultiOptions(Rally_Model_Doctrine_Rally::getFormRisks(),true);
        $this->createElement('submit','submit');
    }
}