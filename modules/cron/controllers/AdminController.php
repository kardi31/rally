<?php

class Cron_Admin extends Controller{
 
    /* + Do it every 15 minutes
     * 1. Calculate rally results
     * 2. 
     * 
     * + Once a day
     * 1. Calculate training report
     * 2. Move transfered players and cars 
     * 3. Pay Premium bonus for invited users
     * 
     * + Once a season
     * 1. Change league for top 3 teams,move inactive teams to 1 bottom league. If team inactive for 6 months, delete team
     * 2. Calculate new player value + salary
     * 3. Calculate new car value + upkeep
     * 4. Create rallies for all leagues
     */
    
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
    
     public function calculateAllTeamsCarValues(){
        $view= $this->view;
        $view->setNoRender();
        $peopleService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        Service::loadModels('rally', 'rally');
        $season = $leagueService->getCurrentSeason();
        $peopleService->calculateNewValuesForAllCars($season);
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
            // if created at least 30 days ago
            if(strtotime($user['created_at'])>strtotime('-30 days')){
                continue;
            }
            // if logged within last 5 days ago
            if(!(strtotime($user['last_active'])>strtotime('-7 days'))){
                $user->referer_not_active = 1;
                $user->save();
                continue;
            }
            
            $values = array();
            $values['description'] = 'Referencing FastRally to user '.$user['username'];
            $values['income'] = 1;
            
            
            if($user['gold_member_expire']!=null){
                $amount = 100;
            }
            else{
                $amount = 10;
            }
            
            
            $userService->addPremium($user['referer'],$amount,$values);
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
            $peopleService->setOffMarket($offerNoBid['people_id']);
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
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        
        /*
         * if friendly rally has less than 4 participants 
         * cancel the rally and give back premium to all members 
         * if field from_gold_member is ticked then dont give prem back
         */
        
        $friendliesNotFinished = $rallyService->getFriendliesNotFinished();
            
        foreach($friendliesNotFinished as $friendly):
//            Zend_Debug::dump($friendly['Participants']->toArray());exit;
            if(count($friendly['Participants'])<3){
                foreach($friendly['Participants'] as $participant){
                    if(!$participant['from_gold_member']){
                        $userService->addPremium($participant['User']['id'],10,'Payback for canceled friendly rally '.$friendly['Rally']['name']);
                    }
                        $notificationService->addNotification('Friendly rally '.$friendly['Rally']['name'].' was canceled due to lack of participants',1,$participant['User']['id']);
                        $participant->delete();
                    
                }
                $userService->addPremium($friendly['User']['id'],10,'Payback for canceled friendly rally '.$friendly['Rally']['name']);
                $notificationService->addNotification('Friendly rally '.$friendly['Rally']['name'].' was canceled due to lack of participants',1,$friendly['User']['id']);
                $rallyService->deleteFriendlyRally($friendly);
                 
            }
            else{
                $friendly->get('Invitations')->delete();
            }
        endforeach;
        
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
