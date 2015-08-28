<?php

class Rally_Test extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function showRally(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $teamService = parent::getService('team','team');
        $teamService = parent::getService('team','team');
        
        $randomTeams = $teamService->selectRandomTeams(15,Doctrine_Core::HYDRATE_ARRAY);
        
        Zend_Debug::dump($randomTeams);exit;
        
        $rally = $rallyService->getRally($GLOBALS['urlParams'][1],'slug');
	
	
        $peopleService = parent::getService('people','people');
        $userService = parent::getService('user','user');
        $carService = parent::getService('car','car');
        $user = $userService->getAuthenticatedUser();
	
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
		
		TK_Helper::redirect('/rally/show-rally/'.$rally['slug']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
	
        
        $this->view->assign('rally',$rally);
        $this->view->assign('form',$form);
    }
    
    public function listRally(){
        
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllFutureRallies();
        $this->view->assign('rallies',$rallies);
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
    
    public function rallyResult($id){
        
        
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        
        $rallyService = parent::getService('rally','rally');
        
        $rally = $rallyService->getRally($id);
        
        $rallyResults = $rallyService->getRallyResults($id);
        
        $this->view->assign('rally',$rally);
        $this->view->assign('rallyResults',$rallyResults);
    }
    
    
}
?>
