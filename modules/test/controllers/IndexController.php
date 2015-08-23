<?php

class Test_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function redirect($param){
        return null;
    }
    
    public function index(){
        require_once(BASE_PATH."/modules/user/controllers/TestController.php");
        $trainingService = parent::getService('people','training');
        $userContr = new User_Test();
        for($i=1;$i<=60;$i++):
        $randomNumber = rand(1000000,1200000);
        $_POST['password'] = "portal";
        $_POST['email'] = "peop_".$randomNumber."@kardimobile.pl";
        $_POST['username'] = "peop_".$randomNumber;
        $user = $userContr->register();
        $GLOBALS['urlParams']['token'] = $user['token'];
        $userContr->activate();
        
        endfor;
        echo "good";exit;
    }
    
    public function rallyTest(){
        Service::loadModels('user', 'user');
        require_once(BASE_PATH."/modules/rally/controllers/TestController.php");
        $trainingService = parent::getService('people','training');
//        $rallyContr = new Rally_Test();
//        $rallyContr->showRally();
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $teamService = parent::getService('team','team');
        $rallyService = parent::getService('rally','rally');
        
        $risks = Rally_Model_Doctrine_Rally::getFormRisks();
               
        $randomTeams = $teamService->selectRandomTeams(15,Doctrine_Core::HYDRATE_ARRAY);
        $randomRally = $rallyService->createRandomRally();
        foreach($randomTeams as $randomTeam):
            $values=array();
            $values['car_id'] = $randomTeam['car1_id'];
            $values['driver_id'] = $randomTeam['driver1_id'];
            $values['pilot_id'] = $randomTeam['pilot1_id'];
            $values['risk'] = $risks[array_rand($risks)];
            $rallyService->saveRallyCrew($values,$randomRally,$randomTeam);
        endforeach;
        var_dump($randomRally->toArray());
        Zend_Debug::dump($randomTeams);exit;
        
    }
    
    public function rallyFriendlyTest(){
        Service::loadModels('user', 'user');
        require_once(BASE_PATH."/modules/rally/controllers/TestController.php");
        $trainingService = parent::getService('people','training');
//        $rallyContr = new Rally_Test();
//        $rallyContr->showRally();
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $teamService = parent::getService('team','team');
        $rallyService = parent::getService('rally','rally');
        
        $risks = Rally_Model_Doctrine_Rally::getFormRisks();
               
        $randomTeams = $teamService->selectRandomTeams(15,Doctrine_Core::HYDRATE_ARRAY);
        $friendly = $rallyService->createRandomFriendly($randomTeams[0]['user_id']);
        foreach($randomTeams as $randomTeam):
            $values=array();
            $values['car_id'] = $randomTeam['car1_id'];
            $values['driver_id'] = $randomTeam['driver1_id'];
            $values['pilot_id'] = $randomTeam['pilot1_id'];
            $values['risk'] = $risks[array_rand($risks)];
            $crew = $rallyService->saveRallyCrew($values,$friendly['Rally'],$randomTeam);
            $rallyService->saveCrewToFriendlyRally($friendly['id'],$crew['id'],$randomTeam['user_id']);
//            $rallyService->saveRallyCrew($values,$randomRally,$randomTeam);
        endforeach;
        Zend_Debug::dump($randomTeams);exit;
        
    }
    
    public function raceRallyTest(){
        require_once(BASE_PATH."/modules/rally/controllers/TestController.php");
        require_once(BASE_PATH."/modules/rally/controllers/IndexController.php");
//        
        $testObj = new Rally_Test();
        $rallyService = parent::getService('rally','rally');
        $teamService = parent::getService('team','team');
        $leagueService = parent::getService('league','league');
        $userService = parent::getService('user','user');
	
        $rally = $rallyService->getRally($GLOBALS['urlParams']['slug'],'slug');
        $rallyStages = $rallyService->getRallyStages($GLOBALS['urlParams']['slug'],'slug',Doctrine_Core::HYDRATE_ARRAY);
        
        foreach($rallyStages as $stage):
            $testObj->calculateStageTime($stage['Rally']['id'],$stage['id']);
        endforeach;
        
	
        $rallyService->calculateRallyResult($rally);
        
        var_dump($rallyStages);exit;
        
        
        echo "good";exit;
    }
    
    public function createRandomMultipleRallies(){
        
        $rallyService = parent::getService('rally','rally');
        for($i=0;$i<15;$i++){
        $randomRally = $rallyService->createRandomRally();
        }
    }
    
    public function rallyLeagueTest(){
        require_once(BASE_PATH."/modules/rally/controllers/TestController.php");
        $trainingService = parent::getService('people','training');
//        $rallyContr = new Rally_Test();
//        $rallyContr->showRally();
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        
        Service::loadModels('user', 'user');
        $teamService = parent::getService('team','team');
        $rallyService = parent::getService('rally','rally');
        $leagueService = parent::getService('league','league');
        
        $risks = Rally_Model_Doctrine_Rally::getFormRisks();
               
        $randomLeague = $leagueService->getRandomLeague();
        $randomTeams = $teamService->selectLeagueFullTeams($randomLeague,true,Doctrine_Core::HYDRATE_ARRAY,5);
        $randomRally = $rallyService->createRandomRally($randomLeague);
        foreach($randomTeams as $randomTeam):
            $values=array();
            $values['car_id'] = $randomTeam['car1_id'];
            $values['driver_id'] = $randomTeam['driver1_id'];
            $values['pilot_id'] = $randomTeam['pilot1_id'];
            $values['risk'] = $risks[array_rand($risks)];
            $rallyService->saveRallyCrew($values,$randomRally,$randomTeam);
        endforeach;
        Zend_Debug::dump($randomTeams);exit;
    }
    
    public function rallyBigLeagueTest(){
        require_once(BASE_PATH."/modules/rally/controllers/TestController.php");
        $trainingService = parent::getService('people','training');
//        $rallyContr = new Rally_Test();
//        $rallyContr->showRally();
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        
        Service::loadModels('user', 'user');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $rallyService = parent::getService('rally','rally');
        $leagueService = parent::getService('league','league');
        
        $risks = Rally_Model_Doctrine_Rally::getFormRisks();
               
        $randomLeague = $leagueService->getRandomLeague();
        $randomTeams = $teamService->selectLeagueFullTeams($randomLeague,true,Doctrine_Core::HYDRATE_ARRAY,5);
        $randomRally = $rallyService->createRandomBigRally($randomLeague);
        foreach($randomTeams as $randomTeam):
            $values=array();
            $values['car_id'] = $randomTeam['car1_id'];
            $values['driver_id'] = $randomTeam['driver1_id'];
            $values['pilot_id'] = $randomTeam['pilot1_id'];
            $values['risk'] = $risks[array_rand($risks)];
            $rallyService->saveRallyCrew($values,$randomRally,$randomTeam);
        endforeach;
        Zend_Debug::dump($randomTeams);exit;
    }
    
    public function createRalliesForAllLeagues(){
        
        $leagueService = parent::getService('league','league');
        $rallyService = parent::getService('rally','rally');
        
        $leagues = $leagueService->getAllActiveLeagues();
        foreach($leagues as $league):
            $league_name = floatval($league['league_name']);
            $rallyService->createRalliesForLeague($league_name);
            exit;
        endforeach;
    }
    
    public function randomDriver(){
        $peopleService = parent::getService('people','people');
        $peopleService->createRandomDriver(3);
        echo "dd";exit;
    }
    
    public function changeRallyNow(){
        
        $rallyService = parent::getService('rally','rally');
//            die('213');
        $rally = $rallyService->getRally($GLOBALS['urlParams']['slug'],'slug');
   
        $newDate = date('Y-m-d H:i:s',strtotime('- 5 hours'));
        
        $rally->set('finished',0);
        $rally->set('date',$newDate);
        
        foreach($rally->get('Stages') as $stage){
            $stage->set('finished',0);
            $stage->set('date',$newDate);
            $stage->save();
        }
        $rally->save();
        echo "good";exit;
    }
}
?>
