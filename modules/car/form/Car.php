<?php

class Car extends Form{
    public function __construct(){
        $name = $this->createElement('text','name',array(),'Name')->addClass('input-medium');
        $this->createElement('text','capacity',array(),'Capacity')->addClass('input-medium');
        $this->createElement('text','horsepower',array(),'Horse power')->addClass('input-medium');
        $this->createElement('text','max_speed',array(),'Max speed')->addClass('input-medium');
        $this->createElement('text','photo',array(),'Photo')->addClass('input-medium');
        $acceleration = $this->createElement('text','acceleration',array(),'Acceleration');
        $acceleration->addClass('input-medium decimalMask');
        $league = $this->createElement('select','league',array(),'League');
        $league->addMultiOptions(array('',1,2,3,4,5));
        $on_market = $this->createElement('radio','on_market',array(),'On market');
        $on_market->addMultiOption(1,'Yes');
        $on_market->addMultiOption(0,'No');
        $this->createElement('text','price',array(),'Price');
        $this->createElement('submit','submit',array(),'Submit');
    }
}

