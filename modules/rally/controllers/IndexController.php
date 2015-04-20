<?php

class Rally_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function showRally(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams']['name'],'slug');
	
	
        $peopleService = parent::getService('people','people');
        $userService = parent::getService('user','user');
        $carService = parent::getService('car','car');
        
        $leagueInt = (int)$rally['league'];
        $crewCounter = count($rally['Crews']);
        if($rally['big_awards']){
            $rallyPrizes = $rallyService->getPrizesHelper()->getBigPrizes($rally,$crewCounter);
        }
        else{
            $rallyPrizes = $rallyService->getPrizesHelper()->getPrizes($leagueInt,$crewCounter);
            $prizePool = $rallyService->getPrizesHelper()->getPrizePool($leagueInt,$crewCounter);
        }
        
        if($rally['finished']){
            $rallyResults = $rallyService->getRallyResults($rally['id'],'rally_id');
            $this->view->assign('rallyResults',$rallyResults);
        }
        
        $user = $userService->getAuthenticatedUser();
	
        if($user){
            $freeDrivers = $peopleService->getFreeDrivers($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
            $freePilots = $peopleService->getFreePilots($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
            $freeCars = $carService->getFreeCars($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
            $form = new Form();

            $driver_id = $form->createElement('select','driver_id',array(),'Kierowca');
            $driver_id->addMultiOptions($freeDrivers,true);
            $pilot_id = $form->createElement('select','pilot_id',array(),'Pilot');
            $pilot_id->addMultiOptions($freePilots,true);
            $car_id = $form->createElement('select','car_id',array(),'Auto');
            $car_id->addMultiOptions($freeCars,true);
            $risk = $form->createElement('select','risk',array('selected' => 'Normal risk'),'Ryzyko');
            $risk->addMultiOptions(Rally_Model_Doctrine_Rally::getFormRisks(),true);
            $form->createElement('submit','submit');

            if($form->isSubmit()){
                if($form->isValid()){
                    Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();

                    $values = $_POST;

                    $rallyService->saveRallyCrew($values,$rally,$user['Team']);

                    TK_Helper::redirect('/rally/show-rally/name/'.$rally['slug']);

                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                }
            }
	
        $this->view->assign('form',$form);
        }
        
        $this->view->assign('rally',$rally);
        $this->view->assign('rallyPrizes',$rallyPrizes);
        $this->view->assign('prizePool',$prizePool);
        $this->view->assign('crewCounter',$crewCounter);
    }
    
    public function listRally(){
        
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllFutureRallies();
        $this->view->assign('rallies',$rallies);
    }
    
    public function listFriendlyRally(){
        
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllFutureFriendlyRallies();
        $this->view->assign('rallies',$rallies);
    }
    
    public function createFriendlyRally(){
        
        $rallyService = parent::getService('rally','rally');
        
        $form = $this->getForm('rally','CreateFriendly');
        
        $this->view->assign('form',$form);
        
    }
    
    public function calculateStageTime(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRallyWithCrews($GLOBALS['urlParams']['name'],'slug');
        $stage = $rallyService->getStageWithResults($GLOBALS['urlParams']['stage'],Doctrine_Core::HYDRATE_ARRAY);
        $peopleService = parent::getService('people','people');
        $carService = parent::getService('car','car');
	$peopleService->getCrewLate($stage,$rally);
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	
	$this->view->assign('rally',$rally);
	$this->view->assign('stage',$stage);
    }
    
    public function rallyResult(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams']['rally'],'slug');
        $rallyResults = $rallyService->getRallyResults($rally['id'],'rally_id');
        $this->view->assign('rally',$rally);
        $this->view->assign('rallyResults',$rallyResults);
    }
    
    
}
?>
