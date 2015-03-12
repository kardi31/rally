<?php

class Market_Admin extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    
    public function calculateAllTeamsPlayerValues(){
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $season = 1;
        $marketService->calculateNewValuesForAllPlayers($season);
        echo "good";exit;
        
        
        $teamService->createRandomTeam($data);
    }
    
    
    
}
?>
