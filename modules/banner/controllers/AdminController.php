<?php

class Banner_Admin extends Controller{
 
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
    
    // do this every day - end
    
    
}
?>
