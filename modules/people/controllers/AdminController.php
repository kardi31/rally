<?php

class People_Admin extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    
    public function calculateAllTeamsPlayerValues(){
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $season = 1;
        $peopleService->calculateNewValuesForAllPlayers($season);
        echo "good";exit;
        
    }
    
    
    
}
?>
