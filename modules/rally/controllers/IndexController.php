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
        $user = $userService->getAuthenticatedUser();
	
	$freeDrivers = $peopleService->getFreeDrivers($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
	$freePilots = $peopleService->getFreePilots($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
	$freeCars = $carService->getFreeCars($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
	$form = new Form();
	
	$form->addMultiOptions('driver_id', $freeDrivers);
	$form->addMultiOptions('car_id', $freeCars);
	$form->addMultiOptions('pilot_id', $freePilots);
	$form->addMultiOptions('risk', Rally_Model_Doctrine_Rally::getFormRisks());
        $form->createElement('select','driver_id',array(),'Kierowca');
        $form->createElement('select','pilot_id',array(),'Pilot');
        $form->createElement('select','car_id',array(),'Auto');
        $form->createElement('select','risk',array('selected' => 'Normal risk'),'Ryzyko');
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
	
        
        $this->view->assign('rally',$rally);
        $this->view->assign('form',$form);
    }
    
    public function listRally(){
        
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllFutureRallies();
        $this->view->assign('rallies',$rallies);
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
    
    
    
    
}
?>
