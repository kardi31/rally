<?php

class Team_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function showTeam(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
	
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        $friendsService = parent::getService('user','friends');
        $leagueService = parent::getService('league','league');
        Service::loadModels('rally', 'rally');
        
        $season = $leagueService->getCurrentSeason();
        $user = $userService->getAuthenticatedUser();
	$team = $teamService->getTeamWithLeague($GLOBALS['urlParams']['id'],$season);
        
        $friendInvited = $friendsService->checkFriendInvited($team['User']['id'],$user['id']);
	
	$this->view->assign('friendInvited',$friendInvited);
	$this->view->assign('team',$team);
        if(!$user){
            $this->getLayout()->setLayout('nolog');
        }
        else{
            $this->getLayout()->setLayout('page');
        }
        
    }
    
    
}
?>
