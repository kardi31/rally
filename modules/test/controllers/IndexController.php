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
        $randomNumber = rand(1000000,1200000);
        $_POST['password'] = "portal";
        $_POST['email'] = "test_".$randomNumber."@kardimobile.pl";
        $user = $userContr->register();
        $GLOBALS['urlParams']['token'] = $user['token'];
        $userContr->activate();
        echo "good";exit;
    }
    
    public function rallyTest(){
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
        Zend_Debug::dump($randomTeams);exit;
        
    }
    
    public function raceRallyTest(){
        require_once(BASE_PATH."/modules/rally/controllers/TestController.php");
        require_once(BASE_PATH."/modules/rally/controllers/IndexController.php");
//        
        $GLOBALS['urlParams']['rally'] = "rally_6848297";
        $rallyController = new Rally_Index();
        $this->actionStack($rallyController,'rallyResult');
        echo "d";exit;
        $testObj = new Rally_Test();
        $testObj->showRallyResult(30);
        echo "ok";exit;
        $rallyService = parent::getService('rally','rally');
        
        $rallyResults = $rallyService->calculateRallyResult(30);
        Zend_Debug::dump($rallyResults);exit;
        
//        
        $rallyStages = $rallyService->getRallyStages($GLOBALS['urlParams']['slug'],'slug',Doctrine_Core::HYDRATE_ARRAY);
        
        foreach($rallyStages as $stage):
            $testObj->calculateStageTime($stage['Rally']['id'],$stage['id']);
        endforeach;
        
        $rallyService->calculateRallyResult($stage['Rally']['id']);
        
        var_dump($rallyStages);exit;
        
        
        echo "good";exit;
    }
    
    
}
?>
