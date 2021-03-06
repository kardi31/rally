<?php

class Cron_Admin extends Controller{
 
    /* + Do it every 5 minutes
     * 1. Add notifications when auction is won.
     * 
     *  + Do it every 15 minutes
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
     * 
     * + Once a week
     * 1. calculateWeeklyCosts - calculate and remove player salaries,car upkeep + add sponsor money
     */
    
    protected $dom;
    
    public function __construct(){
        $this->dom = new DomDocument();
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    // do this every 5 minutes - start
    
    public function notifyTransferedPlayers(){
        
        Service::loadModels('rally', 'rally');
        $peopleService = parent::getService('people','people');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        $notificationService = parent::getService('user','notification');
        
        /*
         * Players
         */
        $offersNoBid = $marketService->getFinishedOffersNotNotifiedNoBid();
        foreach($offersNoBid as $offerNoBid):
            $notificationService->addNotification($offerNoBid['Player']['first_name']." ".$offerNoBid['Player']['last_name']." was not sold. Player will return to your team tomorrow",3,$offerNoBid['Team']['User']['id']);
            $offerNoBid->set('notified',1);
            $offerNoBid->save();
        endforeach;
        
        $offers = $marketService->getFinishedOffersNotNotified();
        // 1. Zaplacenie za transfer przez kupujacego
        
        foreach($offers as $offer): 
            foreach($offer['Bids'] as $key => $bid){
                if($key==0){
                    $notificationService->addNotification($offer['Player']['first_name']." ".$offer['Player']['last_name']." was successfully sold. Player will leave your team tomorrow",3,$offer['Team']['User']['id']);
                    $notificationService->addNotification($offer['Player']['first_name']." ".$offer['Player']['last_name']." has been bought. Player will join your team tomorrow",3,$bid['Team']['User']['id']);
                }
                else{
                    $notificationService->addNotification($offer['Player']['first_name']." ".$offer['Player']['last_name']." has not been bought.",3,$bid['Team']['User']['id']);
                }
            } 
            
        
            $offer->set('notified',1);
            $offer->save();
        endforeach;
        /*
         * Cars
         */
        
        $caroffersNoBid = $marketService->getFinishedCarOffersNotMovedNoBid();
        foreach($caroffersNoBid as $offerNoBid):
            $notificationService->addNotification($offerNoBid['Car']['name']." was not sold. The car will return to your team tomorrow",3,$offerNoBid['Team']['User']['id']);
            $offerNoBid->set('notified',1);
            $offerNoBid->save();
        endforeach;
        
        $caroffers = $marketService->getFinishedCarOffersNotMoved();
        // 1. Zaplacenie za transfer przez kupujacego
        // 2. OTrzymanie kasy przez sprzedajacego
        // 3. Zmiana teamu przez kupujacego
        // 4. Ustawienie oferty na player_moved
        foreach($caroffers as $offer):
            foreach($offer['Bids'] as $key => $bid){
                if($key==0){
                    $notificationService->addNotification($offer['Car']['name']." was successfully sold. The car will leave your team tomorrow",3,$offer['Team']['User']['id']);
                    $notificationService->addNotification($offer['Car']['name']." has been bought. The car will join your team tomorrow",3,$bid['Team']['User']['id']);
                }
                else{
                    $notificationService->addNotification($offer['Car']['name']." has not been bought.",3,$bid['Team']['User']['id']);
                }
            }
            $offer->set('notified',1);
            $offer->save();
        endforeach;
        
        echo "done";exit;
    }
    
    // do this every 5 minutes - finish
    
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
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        Service::loadModels('rally', 'rally');
        $season = $leagueService->getCurrentSeason();
        $carService->calculateNewValuesForAllCars($season);
        echo "good";
    }
    
    
    public function createRalliesForAllLeagues(){
        $view= $this->view;
        $view->setNoRender();
        $leagueService = parent::getService('league','league');
        $rallyService = parent::getService('rally','rally');
        
        $seasonInfo = $leagueService->getSeasonInfo();
        
        $leagues = $leagueService->getAllActiveLeagues();
        foreach($leagues as $league):
            $league_name = $league['league_name'];
            $rallyService->createRalliesForLeague($league_name,$seasonInfo['season_start']);
        endforeach;
        echo "create rallies for league good";
    }
    
    /* do it manually */
    
    public function calculateAllNewCarValues(){
        $view= $this->view;
        $view->setNoRender();
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        Service::loadModels('rally', 'rally');
        $carService->calculateValuesForAllNewCars();
        echo "good";
    }
    
    
    
    /* manually over */
    
    
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
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        
        /*
         * Players
         */
        
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
            $teamService->removeTeamMoney($bid['team_id'],$bid['value'],6,'Bought player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
            $teamService->addTeamMoney($offer['team_id'],$bid['value'],3,'Sold player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
            $peopleService->changePersonTeam($offer['Player']['id'],$bid['team_id']);
            $offer->set('player_moved',1);
            $offer->save();
        endforeach;
        
        /*
         * Cars
         */
        
        $caroffersNoBid = $marketService->getFinishedCarOffersNotMovedNoBid();
        foreach($caroffersNoBid as $offerNoBid):
            $carService->setOffMarket($offerNoBid['car_id']);
            $offerNoBid->set('car_moved',1);
            $offerNoBid->save();
        endforeach;
        
        $caroffers = $marketService->getFinishedCarOffersNotMoved();
        // 1. Zaplacenie za transfer przez kupujacego
        // 2. OTrzymanie kasy przez sprzedajacego
        // 3. Zmiana teamu przez kupujacego
        // 4. Ustawienie oferty na player_moved
        foreach($caroffers as $offer):
            $bid = $offer['Bids'][0];
            $teamService->removeTeamMoney($bid['team_id'],$bid['value'],6,'Bought car '.$offer['Car']['name']);
            $teamService->addTeamMoney($offer['team_id'],$bid['value'],3,'Sold car '.$offer['Car']['name']);
            $carService->changeCarTeam($offer['Car']['id'],$bid['team_id']);
            $offer->set('car_moved',1);
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
    
    /*
     * Do it once a week
     * 
     */
    
    public function calculateWeeklyCosts(){
        $view = $this->view;
        $view->setNoRender();
        
        
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('league', 'league');
        Service::loadModels('car', 'car');
        Service::loadModels('rally', 'rally');
        $teamService = parent::getService('team','team');
        
        /*
         * Wydatki : 
         * 1. Pensje zawodnikow
         * 2. Koszty utrzymania samochodu
         * 
         * Przychody : 
         * 1. Od sponsora (5000)
         */
        
        $teams = $teamService->getAllTeams();
        foreach($teams as $team):
            $playersValue = $teamService->getAllTeamPlayersSalary($team);
            if($playersValue!=0)
                $teamService->removeTeamMoney($team['id'],$playersValue,4,'Player salaries ');  
            
        
            $carUpkeep = $teamService->getAllTeamCarsUpkeep($team);
            if($carUpkeep!=0)
                $teamService->removeTeamMoney($team['id'],$carUpkeep,5,'Cars upkeep');  
            
            if(!empty($team['sponsor_id'])){
                $teamService->addTeamMoney($team['id'],5000,2,'Money from '.$team['Sponsor']['name'].' received on '.date('Y-m-d'));  
            }
            
            if($team['cash']<0){
                $negativeFinances = (int)$team->get('negative_finances');
                $negativeFinances++;
                $team->set('negative_finances',$negativeFinances);
                $team->save();
                
                if($negativeFinances>=5){
                    $team->delete();
                    $user = $team->get('User');
                    $user->set('active',0);
                    $user->save();
                }
            }
            else{
                $team->set('negative_finances',0);
            }
            
            $team->save();
                
        endforeach;
        echo "done";exit;
    }
    
    // on tuesdays
    
    public function calculateTeamRanking(){
        ini_set('max_execution_time',300000);
        Service::loadModels('car', 'car');
        Service::loadModels('people', 'people');
        Service::loadModels('user', 'user');
        Service::loadModels('rally', 'rally');
        Service::loadModels('league', 'league');
        $teamService = parent::getService('team','team');
        $rallyService = parent::getService('rally','rally');
        
        $teamResults = $teamService->getAllTeamsResults();
        
        $teamOrder = array();
        foreach($teamResults as $teamResult):
            $positionExplode = explode(',',$teamResult['group_position']);
            foreach($positionExplode as $position):
                if(!strlen($position)){
                    $teamOrder[$teamResult['id']] = '';
                }
                else{
                    $points = $rallyService->getPrizesHelper()->calculatePointsForPlace($position);
                    if(isset($teamOrder[$teamResult['id']])){
                        $teamOrder[$teamResult['id']] += $points;
                    }
                    else{
                        $teamOrder[$teamResult['id']] = $points;
                    }
                }
            endforeach;
        endforeach;
        arsort($teamOrder);
        
        $key = 1;
        foreach($teamOrder as $team_id => $points){
            $team = $teamService->getTeam($team_id);
            
            if(strlen($team->get('this_week_rank'))){
                $team->set('last_week_rank',$team->get('this_week_rank'));
            }
            
            if($points > 0){
                $team->set('this_week_rank',$key);
            }
            else{
                $team->set('this_week_rank',null);
            }
            $team->save();
            
            $key++;
        }
        
        Zend_Debug::dump($teamOrder);exit;
        
    }
    
    // do it on wednesday
    
    /*
     * 1. Get all players + rallies within last month(not friendly and not big rally)
     * 2. Group rallies by week
     * 3. Check correct number of rallies within last week
     */
    
    public function calculatePlayerForm(){
        ini_set('max_execution_time',300000);
        Service::loadModels('car', 'car');
        Service::loadModels('people', 'people');
        Service::loadModels('user', 'user');
        Service::loadModels('rally', 'rally');
        Service::loadModels('league', 'league');
        $teamService = parent::getService('team','team');
        $peopleService = parent::getService('people','people');
        $rallyService = parent::getService('rally','rally');
        
        // get all player rallies from last 4 weeks
        $playerRallies = $peopleService->getLastMonthPlayersRallies(Doctrine_Core::HYDRATE_ARRAY);
        
        // calculate actual form
        $playerFormCalculatedData = $peopleService->preparePlayerRalliesWeekly($playerRallies);
        
        // get player ids which are already calculated
        // this allows to get all players which has not raced within last month and set their form to 0
        $playerIds = $playerFormCalculatedData['playerIds'];
        
        $teamOrder = array();
        foreach($playerFormCalculatedData['playerList'] as $player_id => $playerRow):
            $newForm = $playerRow['form'];
            $player = $peopleService->getPerson($player_id);
            $player->set('form',$newForm);
            $player->save();
        endforeach;
        
        $noRalliesPlayers = $peopleService->getPlayersNoLastMonthRallies($playerIds);
        foreach($noRalliesPlayers as $player):
            $newForm = 0;
            $player->set('form',$newForm);
            $player->save();
        endforeach;
        echo "done";
        exit;
        
    }
    
    /*
     * Do it once a week - end
     * 
     */
    
    // do this every 15 min 
    
    public function calculateRallyResult(){
       
        ini_set('max_execution_time', 300);
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $teamService = parent::getService('team','team');
        parent::getService('car','car');
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
    // start season on monday morning
    // finish on monday morning
    public function startSeason(){
        ini_set('max_execution_time',300000);
        $rallyDataService = parent::getService('rally','rallyData');
        $rallyService = parent::getService('rally','rally');
        $leagueService = parent::getService('league','league');
        
        $seasonInfo = $leagueService->getSeasonInfo();
        
        $rallies = $rallyDataService->getAllRallies();
        foreach($rallies as $rally):
            $rallyService->saveRallyFromData($rally,$seasonInfo['season_start']);
            $rallyService->saveRallyFromData($rally,$seasonInfo['season_start'],2);
            $rallyService->saveRallyFromData($rally,$seasonInfo['season_start'],3);
        endforeach;
        
        $this->createRalliesForAllLeagues();
        echo "done";exit;
    }
    
    public function calculateCarValues(){
        ini_set('max_execution_time',300000);
        $carService = parent::getService('car','car');
        
        $cars = $carService->getAllCars();
        
        foreach($cars as $key=>$car):
            $car->set('value',$car['Model']['price']);
            $car->set('upkeep',0.15*$car['Model']['price']);
            $car->set('name',$car['Model']['name'].' #'.$key);
            $car->save();
        endforeach;
        
        echo "done";exit;
    }
    
    public function promoteTeams(){
        ini_set('max_execution_time',300000);
        Service::loadModels('team', 'team');
        Service::loadModels('user', 'user');
        Service::loadModels('rally', 'rally');
        $leagueService = parent::getService('league','league');
        parent::getService('rally','rally');
        parent::getService('team','team');
        
        
        $leagueTeamsArray =  $leagueService->selectTeamsForPromotion(1);
        
        $leagueService->promoteTeams($leagueTeamsArray);
    }
   
    
    // create sitemap
    
    public function createSitemap(){
        header("Content-Type: text/plain");
        $doc->preserveWhiteSpace = TRUE;
        $this->dom->formatOutput = true;
        
        $urlset = $this->dom->createElement('urlset');
        $urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation','http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        
        
        $this->addUrl($urlset,'http://fastrally.eu/');
        $this->addUrl($urlset,'http://fastrally.eu/rules');
        $this->addUrl($urlset,'http://fastrally.eu/privacy-policy');
        $this->addUrl($urlset,'http://fastrally.eu/manual');
        $this->addUrl($urlset,'http://fastrally.eu/faq');
        $this->addUrl($urlset,'http://fastrally.eu/privacy-policy?lang=pl');
        $this->addUrl($urlset,'http://fastrally.eu/manual?lang=pl');
        $this->addUrl($urlset,'http://fastrally.eu/faq?lang=pl');
        $this->addUrl($urlset,'http://fastrally.eu/?lang=pl');
        
        $teamService = $this->getService('team', 'team');
        $rallyService = $this->getService('rally', 'rally');
        $teams = $teamService->getAllTeams();
        
        foreach($teams as $team):
            $this->addUrl($urlset,'http://fastrally.eu/team/show-team/'.$team['id']);
        endforeach;
        
        
        $this->dom->appendChild($urlset);
        
        $this->dom->save(BASE_PATH."/public_html/sitemap.xml");
       echo "done";
        exit;
    }
    
    protected function addUrl($urlset,$url){
        $urlElem = $this->dom->createElement('url');
        
        $urlLoc = $this->dom->createElement('loc',$url);
        
        $urlElem->appendChild($urlLoc);
        
        $urlset->appendChild($urlElem);
    }
    
}
?>
