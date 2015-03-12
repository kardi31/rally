<?php

class Market_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function createRandomMarket(){
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $data = array();
        $data['driver1_id'] = $marketService->createRandomDriver(5)->get('id');
        $data['driver2_id'] = $marketService->createRandomDriver(5)->get('id');
        $data['pilot1_id'] = $marketService->createRandomPilot(5)->get('id');
        $data['pilot2_id'] = $marketService->createRandomPilot(5)->get('id');
        
        $teamService->createRandomTeam($data);
    }
    
    
    
}
?>
