<?php

class FreeAgent extends Form{
    public function __construct(){
        $view = View::getInstance();
        $job = $this->createElement('radio','job');
        $job->addClass('form-control');
        $job->addParam('required');
        $job->addMultiOptions(array(
            'driver' => 'Choose a driver',
            'pilot' => 'Choose a pilot'
            
            ));
        
        $submit = $this->createElement('submit','submit');
        $submit->addClass('btn btn-success');
        $submit->setLabel($view->translate('Get free agent'));
    }
}

