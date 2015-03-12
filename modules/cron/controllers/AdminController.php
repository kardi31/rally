<?php

class Cron_Admin extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    // do this every season - start
    
    public function calculateAllTeamsPlayerValues(){
        $view= $this->view;
        $view->setNoRender();
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        Service::loadModels('rally', 'rally');
        $season = $leagueService->getCurrentSeason();
        $peopleService->calculateNewValuesForAllPlayers($season);
        echo "good";
    }
    
    // do this every season - end
    
    // do this every day - start
    
    // do this every day - end
    
}
?>
