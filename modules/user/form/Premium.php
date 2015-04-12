<?php

class Premium extends Form{
    public function __construct(){
        $this->createElement('text','premium',array('validators' => array('int')),'Premium');
        $this->createElement('submit','submit');
    }
}

