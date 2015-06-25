<?php

class Rally_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
        
        $this->getLayout()->setLayout('page');
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function showRally(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams']['slug'],'slug');
	
        $peopleService = parent::getService('people','people');
        $userService = parent::getService('user','user');
        $carService = parent::getService('car','car');
        
        $user = $userService->getAuthenticatedUser();
        
        $leagueInt = (int)$rally['league'];
        $crewCounter = count($rally['Crews']);
        if($rally['big_awards']){
            $rallyPrizes = $rallyService->getPrizesHelper()->getBigPrizes($rally,$crewCounter);
        }
        else{
            $rallyPrizes = $rallyService->getPrizesHelper()->getPrizes($leagueInt,$crewCounter);
            $prizePool = $rallyService->getPrizesHelper()->getPrizePool($leagueInt,$crewCounter);
        }
        
        
        $isParticipant = $rallyService->getRallyParticipant($rally['id'],$user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        if($rally['finished']){
            $rallyResults = $rallyService->getRallyResults($rally['id'],'rally_id');
            $this->view->assign('rallyResults',$rallyResults);
        }
        
        
	
        if($user){
            $freeDrivers = $peopleService->getFreeDrivers($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
            $freePilots = $peopleService->getFreePilots($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
            $freeCars = $carService->getFreeCars($user['Team'],$rally['date'],Doctrine_Core::HYDRATE_ARRAY);
	
            $form = $this->getForm('rally','JoinRally');
            $form->getElement('driver_id')->addMultiOptions($freeDrivers,true);
            $form->getElement('pilot_id')->addMultiOptions($freePilots,true);
            $form->getElement('car_id')->addMultiOptions($freeCars,true);
            $this->view->assign('form',$form);

            if($form->isSubmit()){
                if($form->isValid()){
                    Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();

                    $values = $_POST;

                    $rallyService->saveRallyCrew($values,$rally,$user['Team']);

                    TK_Helper::redirect('/rally/show-rally/slug/'.$rally['slug']);

                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                }
            }
	
        $this->view->assign('form',$form);
        }
        
        $startDate = new DateTime($rally['date']);
        $signUpFinish = clone $startDate;
        $signUpFinish->sub(new DateInterval('PT15M'));
        
        $this->view->assign('isParticipant',$isParticipant);
        $this->view->assign('startDate',$startDate);
        $this->view->assign('signUpFinish',$signUpFinish);
        $this->view->assign('rally',$rally);
        $this->view->assign('rallyPrizes',$rallyPrizes);
        $this->view->assign('prizePool',$prizePool);
        $this->view->assign('crewCounter',$crewCounter);
    }
    
    public function listRally(){
        
        Service::loadModels('team', 'team');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllFutureRallies();
        
        $futureTeamRallies = $rallyService->getAllFutureTeamRallies($user['Team']['id'],Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        $this->view->assign('rallies',$rallies);
        $this->view->assign('futureTeamRallies',$futureTeamRallies);
    }
    
    public function myRallies(){
        
        Service::loadModels('team', 'team');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $rallyService = parent::getService('rally','rally');
//        $rallies = $rallyService->getAllFutureRallies();
        
        $rallies = $rallyService->getAllTeamRallies($user['Team']['id'],Doctrine_Core::HYDRATE_RECORD);

        $this->view->assign('rallies',$rallies);
//        $this->view->assign('futureTeamRallies',$futureTeamRallies);
    }
    
    public function listFriendlyRally(){
        
        Service::loadModels('team', 'team');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllFutureFriendlyRallies();
        
        
        $futureTeamRallies = $rallyService->getAllFutureTeamFriendlyRallies($user['Team']['id'],Doctrine_Core::HYDRATE_SINGLE_SCALAR);

        
        $this->view->assign('rallies',$rallies);
        $this->view->assign('futureTeamRallies',$futureTeamRallies);
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
        
        $recentUserFriendlies = $rallyService->countRecentUserFriendlies($user['id']);
        $joinForm = $this->getForm('rally','JoinRally');
        $joinForm->getElement('driver_id')->addMultiOptions($freeDrivers,true);
        $joinForm->getElement('pilot_id')->addMultiOptions($freePilots,true);
        $joinForm->getElement('car_id')->addMultiOptions($freeCars,true);
        $joinForm->getElement('driver_id')->addParam('disabled');
        $joinForm->getElement('pilot_id')->addParam('disabled');
        $joinForm->getElement('car_id')->addParam('disabled');
        $this->view->assign('joinForm',$joinForm);
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;

                if($user['gold_member']&&$recentUserFriendlies<2){
                    $friendly = $rallyService->saveFriendlyRally($values,$user);
                    $crew = $rallyService->saveRallyCrew($values,$friendly['Rally'],$user['Team']);
                    $rallyService->saveCrewToFriendlyRally($friendly['id'],$crew['id'],$user['id']);

                    TK_Helper::redirect('/rally/show-friendly-rally/slug/'.$friendly['Rally']['slug']);
                }
                elseif(!$userService->checkUserPremium($user['id'],10)){
                    $this->view->assign('message','You do not have enough premium. Please buy more premium');
                }
                else{
                    $friendly = $rallyService->saveFriendlyRally($values,$user);
                    $crew = $rallyService->saveRallyCrew($values,$friendly['Rally'],$user['Team']);
                    $rallyService->saveCrewToFriendlyRally($friendly['id'],$crew['id'],$user['id']);

                    TK_Helper::redirect('/rally/show-friendly-rally/slug/'.$friendly['Rally']['slug']);
                }
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        
        $this->view->assign('recentUserFriendlies',$recentUserFriendlies);
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
        
        $isParticipant = $rallyService->getFriendlyParticipant($friendly['id'],$user['username'],Doctrine_Core::HYDRATE_ARRAY);
        
        if($user['id']==$friendly['user_id']&&$friendly['invite_only']==1){
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
        elseif($rallyService->getFriendlyInvitedUser($friendly['id'],$user['username'])||$friendly['invite_only']==0&&!$isParticipant){
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
        
        
        
        
        $this->view->assign('isParticipant',$isParticipant);
        $this->view->assign('friendly',$friendly);
        
    }
    
    public function checkAvailability(){
        Service::loadModels('team', 'team');
        Service::loadModels('rally', 'rally');
        $date = TK_Text::timeFormat($_POST['date'],'Y-m-d H:i:s','d-m-Y');
        if(empty($date)){
            $result['result'] = 'no data';
            echo json_encode($result);
            exit;
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        $peopleService = parent::getService('people','people');
        $carService = parent::getService('car','car');
        $freeDrivers = $peopleService->getFreeDriversFriendly($user['Team'],$date,Doctrine_Core::HYDRATE_ARRAY);
        $freePilots = $peopleService->getFreePilotsFriendly($user['Team'],$date,Doctrine_Core::HYDRATE_ARRAY);
        $freeCars = $carService->getFreeCarsFriendly($user['Team'],$date,Doctrine_Core::HYDRATE_ARRAY);
        
        $result['result']['message'] = '';
        if(empty($freeDrivers)){
            $result['result']['message'] .= 'No available drivers for this date<br />'; 
        }
        if(empty($freePilots)){
            $result['result']['message'] .= 'No available pilots for this date<br />'; 
        }
        if(empty($freeCars)){
            $result['result']['message'] .= 'No available cars for this date<br />'; 
        }
        if(strlen($result['result']['message'])){
            $result['result']['message'] = '<div class="alert alert-danger alert-dismissable">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            '.$result['result']['message'].'
        </div>';
            
        }
        
        
        $joinForm = $this->getForm('rally','JoinRally');
        $joinForm->getElement('driver_id')->addMultiOptions($freeDrivers,true);
        $joinForm->getElement('pilot_id')->addMultiOptions($freePilots,true);
        $joinForm->getElement('car_id')->addMultiOptions($freeCars,true);
        
        $result['result']['driver_id'] = $joinForm->getElement('driver_id')->renderElement();
        $result['result']['pilot_id'] = $joinForm->getElement('pilot_id')->renderElement();
        $result['result']['car_id'] = $joinForm->getElement('car_id')->renderElement();
        $result['result']['success'] = "true";
        echo json_encode($result);
        exit;
//        var_dump('t');exit;
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
        $rally = $rallyService->getRally($GLOBALS['urlParams']['id'],'id');
        if($rally['finished']){
            $rallyResults = $rallyService->getRallyResults($rally['id'],'rally_id');
        }
        else{
            $rallyResults = $rallyService->calculatePartialRallyResult($rally);
        }
        $rallyStagesResults = $rallyService->getRallyStagesResults($rally['id']);
        $this->view->assign('rally',$rally);
        $this->view->assign('rallyResults',$rallyResults);
        $this->view->assign('rallyStagesResults',$rallyStagesResults);
    }
    
    
    
    
}
?>
