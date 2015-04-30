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
        
        Service::loadModels('team', 'team');
        $rallyService = parent::getService('rally','rally');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('rally','CreateFriendly');
        
        $peopleService = parent::getService('people','people');
        $carService = parent::getService('car','car');
        $freeDrivers = $peopleService->getFreeDriversFriendly($user['Team'],null,Doctrine_Core::HYDRATE_ARRAY);
        $freePilots = $peopleService->getFreePilotsFriendly($user['Team'],null,Doctrine_Core::HYDRATE_ARRAY);
        $freeCars = $carService->getFreeCarsFriendly($user['Team'],null,Doctrine_Core::HYDRATE_ARRAY);
            
        $joinForm = $this->getForm('rally','JoinRally');
        $joinForm->getElement('driver_id')->addMultiOptions($freeDrivers,true);
        $joinForm->getElement('pilot_id')->addMultiOptions($freePilots,true);
        $joinForm->getElement('car_id')->addMultiOptions($freeCars,true);
        $this->view->assign('joinForm',$joinForm);
        
        if($form->isSubmit()){
            if($form->isValid()){
                var_dump($_POST);exit;
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;

                $rally = $rallyService->saveFriendlyRally($values,$user);

                TK_Helper::redirect('/rally/show-friendly-rally/slug/'.$rally['slug']);

                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        
        $this->view->assign('form',$form);
        
    }
    
    public function showFriendlyRally(){
        
        Service::loadModels('team', 'team');
        $rallyService = parent::getService('rally','rally');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        if(!$friendly = $rallyService->getFriendlyRally($GLOBALS['urlParams']['slug'],'r.slug',Doctrine_Core::HYDRATE_ARRAY)){
            echo "blad";exit;
        }
        
        
        $invitedUsers = $rallyService->getFriendlyInvitedUsers($friendly,Doctrine_Core::HYDRATE_ARRAY);
        $this->view->assign('invitedUsers',$invitedUsers);
        
        if($user['id']==$friendly['user_id']&&$friendly->invite_only){
            $form = $this->getForm('rally','InviteFriendly');
            
            
            $this->view->assign('form',$form);
            
            if($form->isSubmit()){
                if($form->isValid()){
                    Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();

                    $values = $_POST;
                    if(!$friendlyUser = $userService->getUser($values['name'],'username',Doctrine_Core::HYDRATE_ARRAY)){
                        $this->view->assign('message','This user does not exists');
                    }
                    elseif($rallyService->getFriendlyInvitedUser($friendly['id'],$values['name'])){
                        $this->view->assign('message','This user was already invited');
                    }
                    else{
                        $rally = $rallyService->inviteUserToFriendlyRally($friendly,$friendlyUser);
                        unset($_POST);
                        
                        TK_Helper::redirect('/rally/show-friendly-rally/slug/'.$GLOBALS['urlParams']['slug']);
                    }


                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                }
            }
            
            
        }
        elseif($rallyService->getFriendlyInvitedUser($friendly['id'],$user['username'])||!$friendly->invite_only){
            $peopleService = parent::getService('people','people');
            $carService = parent::getService('car','car');
            $freeDrivers = $peopleService->getFreeDriversFriendly($user['Team'],$friendly['Rally']['date'],Doctrine_Core::HYDRATE_ARRAY);
            $freePilots = $peopleService->getFreePilotsFriendly($user['Team'],$friendly['Rally']['date'],Doctrine_Core::HYDRATE_ARRAY);
            $freeCars = $carService->getFreeCarsFriendly($user['Team'],$friendly['Rally']['date'],Doctrine_Core::HYDRATE_ARRAY);
            
            $joinForm = $this->getForm('rally','JoinRally');
            $joinForm->getElement('driver_id')->addMultiOptions($freeDrivers,true);
            $joinForm->getElement('pilot_id')->addMultiOptions($freePilots,true);
            $joinForm->getElement('car_id')->addMultiOptions($freeCars,true);
            $this->view->assign('joinForm',$joinForm);
            
            if($joinForm->isSubmit()){
                if($joinForm->isValid()){
                    Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();

                    $values = $_POST;
                    
                    $crew = $rallyService->saveRallyCrew($values,$friendly['Rally'],$user['Team']);
                    $rallyService->saveCrewToFriendlyRally($friendly['id'],$crew['id'],$user['id']);
                    $rallyService->removeFriendlyInvite($friendly['id'],$user['username']);
                    unset($_POST);

                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                    
                    TK_Helper::redirect('/rally/show-friendly-rally/slug/'.$GLOBALS['urlParams']['slug']);

                }
            }
        }
        elseif($rallyService->getFriendlyParticipant($friendly['id'],$user['username'])){
            $this->view->assign('participant',true);
        }
        
        
        
        
        $this->view->assign('friendly',$friendly);
        
    }
    
    
    public function removeFriendlyRallyParticipant(){
        
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        Service::loadModels('team', 'team');
        $rallyService = parent::getService('rally','rally');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        if(!$friendly = $rallyService->getFriendlyRally($GLOBALS['urlParams']['slug'],'r.slug',Doctrine_Core::HYDRATE_ARRAY)){
            echo "blad";exit;
        }
        
        
        if(!$invitedUser = $rallyService->getFriendlyParticipant($friendly['id'],$user['username'],Doctrine_Core::HYDRATE_RECORD)){
            echo "blad";exit;
        }
        
        $invitedUser->get('Crew')->delete();
        $invitedUser->delete();
         
        TK_Helper::redirect('/rally/show-friendly-rally/slug/'.$GLOBALS['urlParams']['slug']);

        $this->view->assign('friendly',$friendly);
        
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
    
    public function checkAvailability(){
        $date = $_POST['date'];
        $driver_id = $_POST['driver_id'];
        $pilot_id = $_POST['pilot_id'];
        $car_id = $_POST['car_id'];
        if(empty($date)){
            $result['result'] = 'no data';
        }
        echo json_encode($result);
        exit;
//        var_dump('t');exit;
    }
    
    
}
?>
