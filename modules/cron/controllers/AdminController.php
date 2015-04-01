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
    
    public function createRalliesForAllLeagues(){
        $view= $this->view;
        $view->setNoRender();
        $leagueService = parent::getService('league','league');
        $rallyService = parent::getService('rally','rally');
        
        $leagues = $leagueService->getAllActiveLeagues();
        foreach($leagues as $league):
            $league_name = floatval($league['league_name']);
            $rallyService->createRalliesForLeague($league_name);
        endforeach;
        echo "create rallies for league good";
    }
    
    // do this every season - end
    
    // do this every day - start
    
    // do this every day - end
    
}
?>
