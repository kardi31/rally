<?php

class League_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
    }
    
    public function showTable(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('league', 'league');
        Service::loadModels('car', 'car');
	
        $leagueService = parent::getService('league','league');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	$league = $leagueService->getTeamLeague($user['Team']['id']);
        
        $leagueTable = $leagueService->getLeagueTable($league['league_name']);
	$this->view->assign('leagueTable',$leagueTable);
	$this->view->assign('league',$league);
    }
    
    
    
}
?>
