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
    
    public function payForInvitedUsers(){
        
        $userService = parent::getService('user','user');
        
        $refererUsers = $userService->getUsersWithRefererNotPaid();
        foreach($refererUsers as $user):
            // if created at least 7 days ago
            if(strtotime($user['created_at'])>strtotime('-7 days')){
                continue;
            }
            // if logged within last 3 days ago
            if(!(strtotime($user['last_active'])>strtotime('-3 days'))){
                continue;
            }
            
            $values = array();
            $values['description'] = 'Referencing FastRally to user '.$user['username'];
            $values['income'] = 1;
            $userService->addPremium($user['referer'],10,$values);
            $user->referer_paid = 1;
            $user->save();
        endforeach;
        echo "done";exit;
    }
    
    public function moveTransferedPlayers(){
        
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        
        $offersNoBid = $marketService->getFinishedOffersNotMovedNoBid();
        foreach($offersNoBid as $offerNoBid):
            $peopleService->setOffMarket($offerNoBid['player_id']);
            $offerNoBid->set('player_moved',1);
            $offerNoBid->save();
        endforeach;
        
        $offers = $marketService->getFinishedOffersNotMoved();
        // 1. Zaplacenie za transfer przez kupujacego
        // 2. OTrzymanie kasy przez sprzedajacego
        // 3. Zmiana teamu przez kupujacego
        // 4. Ustawienie oferty na player_moved
        foreach($offers as $offer):
            $bid = $offer['Bids'][0];
            $teamService->removeTeamMoney($bid['team_id'],$bid['value'],6,'Arrival of player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
            $teamService->addTeamMoney($offer['team_id'],$bid['value'],3,'Sell of player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
            $peopleService->changePersonTeam($offer['Player']['id'],$bid['team_id']);
            $offer->set('player_moved',1);
            $offer->save();
        endforeach;
        echo "done";exit;
    }
    
    public function calculateTraining(){
        $view = $this->view;
        $view->setNoRender();
        
        
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $trainingService = parent::getService('people','training');
        
        $crews = $rallyService->getCrewsWithNotCompletedTrainingToday(Doctrine_Core::HYDRATE_ARRAY);
        $trainingService->calculateTraining($crews,$rallyService);
        
        echo "done";exit;
    }
    
    // do this every day - end
    
    // do this every 15 min 
    
    public function calculateRallyResult(){
        $rallyService = parent::getService('rally','rally');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        
        
        $ralliesWithNotFinishedStages = $rallyService->getRalliesWithNotFinishedStages();
        foreach($ralliesWithNotFinishedStages as $rally):
            foreach($rally['Stages'] as $stage):
                $this->calculateStageTime($stage['Rally']['id'],$stage['id']);
                $stage->set('finished',1);
                $stage->save();
            endforeach;
            
        endforeach;
        
        $rallyToFinish = $rallyService->getRalliesToFinish();
        foreach($rallyToFinish as $rally):
            $rallyService->calculateRallyResult($rally);
        endforeach;
                
        
        echo "pp";exit;
        
    }
    
    public function calculateStageTime($rally_id,$stage_id){
	 
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $crews = $rallyService->getRallyCrews($rally_id,'rally_id',Doctrine_Core::HYDRATE_RECORD);
	$stage = $rallyService->getStageShort($stage_id,'id',Doctrine_Core::HYDRATE_ARRAY);
	// get array with id of crews which's time hasn't been calculated yet
	$crewsWithResults = $rallyService->getCrewsWithoutResults($stage['id'],Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$surfaces = $rallyService->getRallySurfaces($rally_id,Doctrine_Core::HYDRATE_ARRAY);
        
        $carService = parent::getService('car','car');
        $trainingService = parent::getService('people','training');
        $peopleService = parent::getService('people','people');
	$peopleService->runStageForCrew($stage,$crews,$crewsWithResults,$surfaces);
	
    }
    
    // 15 min finish
    
    // random scripts
    
    public function calculateStageDate(){
        $rallyService = parent::getService('rally','rally');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        
        $allRallies = $rallyService->getAllRallies();
        foreach($allRallies as $rally):
            $date = new DateTime($rally['date']);
            foreach($rally['Stages'] as $key => $stage):
            if($key!=0){
                $date->add(new DateInterval('PT15M'));
            }
                $stage->set('date',$date->format('Y-m-d H:i:s'));
                if($date->format('Y-m-d H:i:s')<date('Y-m-d H:i:s')){
                    $stage->set('finished',1);
                }
                $stage->save();
//                var_dump($stage->toArray());exit;
            endforeach;
        endforeach;
        echo "pp";exit;
    }
}
?>
