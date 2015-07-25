<?php
class JoinRally extends Form{
    public function __construct(){
        $view = View::getInstance();
        $driver_id = $this->createElement('select','driver_id',array(),'Kierowca');
        $driver_id->addParam('required');
        $driver_id->addParam('class','checkJoin');
        $pilot_id = $this->createElement('select','pilot_id',array(),'Pilot');
        $pilot_id->addParam('required');
        $pilot_id->addParam('class','checkJoin');
        $car_id = $this->createElement('select','car_id',array(),'Auto');
        $car_id->addParam('required');
        $car_id->addParam('class','checkJoin');
        $risk = $this->createElement('select','risk',array('selected' => 'Normal risk'),'Ryzyko');
        $risk->addMultiOptions(Rally_Model_Doctrine_Rally::getFormRisks(),true);
        $risk->setValue('normal');
        $submit = $this->createElement('submit','submit');
        $submit->setLabel($view->translate('Join rally'));
        $submit->addClass('btn myBtn');
    }
}