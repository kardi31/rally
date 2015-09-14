<?php

class Team_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function showTeam(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
	
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        $friendsService = parent::getService('user','friends');
        $leagueService = parent::getService('league','league');
        $carService = parent::getService('car','car');
        $rallyService = parent::getService('rally','rally');
        
        $season = $leagueService->getCurrentSeason();
        $user = $userService->getAuthenticatedUser();
        
        
        if(!$team = $teamService->getTeamWithLeague($GLOBALS['urlParams'][1],$season)){
            throw new TK_Exception('No such team',404);
            exit;
        }
        
        $friendInvited = $friendsService->checkFriendInvited($team['User']['id'],$user['id']);
	
//        if(strlen(TK_Helper::getRealIpAddr())&&TK_Helper::getRealIpAddr()!='::1'){
//            $realIp = TK_Helper::getRealIpAddr();
//        }
//        else{
//            $realIp = '2.218.26.230';
//        }
//        $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$realIp);
//        
//        $this->view->assign('countryCode',$xml->geoplugin_countryCode);
        
	$awards = $rallyService->getTeamAwards($team['id']);
        
        $models = $carService->getAllModelsWithPhoto();
        
	$this->view->assign('friendInvited',$friendInvited);
	$this->view->assign('team',$team);
	$this->view->assign('models',$models);
	$this->view->assign('awards',$awards);
        if(!$user){
            $this->getLayout()->setLayout('nolog');
        }
        else{
            $this->getLayout()->setLayout('page');
        }
        
    }
    
    public function changeName(){
        $this->getLayout()->setLayout('page');
        
        Service::loadModels('rally', 'rally');
	
        $teamService = parent::getService('team','team');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
//        $id = $_POST['car_id'];
        try{
            $team = $teamService->getTeam($user['Team']['id'],'id',Doctrine_Core::HYDRATE_RECORD);
        } catch (Exception $ex) {
            TK_Helper::redirect('/team/show-team/'.$user['Team']['id']);
            exit;
        }
        
                
	$form = $this->getForm('car','NameChange');
        
        $form->populate($team->toArray());
	
        // all members can change team name every 90 days
        $allowedDiff = 90;
        
        $last_name_change_date = $team['last_name_change'];
        $nextDateChange = false;
        if($last_name_change_date!= "0000-00-00 00:00:00" && $last_name_change_date != null){
            
            // last name change date
            $date1 = new DateTime($last_name_change_date);

            // now date
            $date2 = new DateTime();

            // calculate diff between dates
            $diff = $date2->diff($date1)->format("%a");

            if( $diff < $allowedDiff){
                $nextDateChange = $date1->add(new DateInterval('P'.$allowedDiff.'D'));
                $form->getElement('name')->addParam('disabled');
                $form->getElement('submit')->addParam('disabled');
            }
        }
        
//                
	if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                $values = $_POST;
		
		$last_name_change_date = $team['last_name_change'];
		if($last_name_change_date!= "0000-00-00 00:00:00" && $last_name_change_date!= null){
		    
		    // last name change date
		    $date1 = new DateTime($last_name_change_date);
		    
		    // now date
		    $date2 = new DateTime();
		    
		    // calculate diff between dates
		    $diff = $date2->diff($date1)->format("%a");
		    
		    
		    if( $diff >= $allowedDiff){
			$team->set('name',$values['name']);
			$team->set('last_name_change',date('Y-m-d H:i:s'));
                        $team->save();
			TK_Helper::redirect('/team/show-team/'.$team['id']);
		    }
		    else{
			$form->setError('Można dokonać 1 zmiany nazwy na 90 dni');
		    }
		}
		else{
                    $team->set('name',$values['name']);
                    $team->set('last_name_change',date('Y-m-d H:i:s'));
                    $team->save();
                    TK_Helper::redirect('/team/show-team/'.$team['id']."?msg=name+changed");
		}
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
	
	$this->view->assign('nextDateChange',$nextDateChange);
	$this->view->assign('team',$team);
	$this->view->assign('form',$form);
    }
    
    public function cancelSponsorship(){
        $this->getLayout()->setLayout('page');
        
        Service::loadModels('rally', 'rally');
	
        $teamService = parent::getService('team','team');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        if(!$user)
            TK_Helper::redirect('/');
        
        
        $team = $user->get('Team');
        $team->set('sponsor_id',null);
        $team->save();
	
        TK_Helper::redirect('/account/sponsor/?msg=canceled');
    }
    
    
    public function nationalRanking(){
        $this->getLayout()->setLayout('page');
        Service::loadModels('user', 'user');
	
        $teamService = parent::getService('team','team');
        
        if(isset($GLOBALS['urlParams'][1]))
        {
            $this->setDifView('team', 'national-ranking-country');
            $topCountryList = $teamService->getTopCountryList($GLOBALS['urlParams'][1],Doctrine_Core::HYDRATE_ARRAY);
            $this->view->assign('topCountryList',$topCountryList);
            
            $country = TK_Helper::getCountry(strtoupper($GLOBALS['urlParams'][1]));
            if(!$country){
                throw new TK_Exception('No such page',404);
            }
            $this->view->assign('country',$country);
            
        }
        else{
            $countries = $teamService->getActiveCountries(Doctrine_Core::HYDRATE_SCALAR);
            $this->view->assign('countries',$countries);
        }
        
    }
    
}
?>
