<?php

class People_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function createRandomPeople(){
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $data = array();
        $data['driver1_id'] = $peopleService->createRandomDriver(5)->get('id');
        $data['driver2_id'] = $peopleService->createRandomDriver(5)->get('id');
        $data['pilot1_id'] = $peopleService->createRandomPilot(5)->get('id');
        $data['pilot2_id'] = $peopleService->createRandomPilot(5)->get('id');
        
        $teamService->createRandomTeam($data);
    }
    
    
    
}
?>
