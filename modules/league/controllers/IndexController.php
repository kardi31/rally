<?php

class League_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
        $this->getLayout()->setLayout('page');
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
        
        $league = TK_Text::dotDash($GLOBALS['urlParams']['id']);
        $leagueTable = $leagueService->getLeagueTable(floatval($league));
	$this->view->assign('leagueTable',$leagueTable);
	$this->view->assign('league',$league);
    }
    
    
    
}
?>
