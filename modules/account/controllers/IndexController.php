<?php

class Account_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
        $this->getLayout()->setLayout('page');
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
   
    
    public function myAccount(){
        Service::loadModels('team', 'team');
        $userService = parent::getService('user','user');
        $newsService = parent::getService('news','news');
        
        $lastNewses = $newsService->getLastNewses(3);
        
        $this->view->assign('lastNewses',$lastNewses);
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/');
        
        $this->view->assign('user',$user);
        $this->getLayout()->setLayout('layout');
        
    }
    
    public function tactics(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        Service::loadModels('people', 'people');
        
        $peopleService = parent::getService('people','people');
        $userService = parent::getService('user','user');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $teamDrivers = $peopleService->prepareTeamDrivers($user['Team']['id']);
        $teamPilots = $peopleService->prepareTeamPilots($user['Team']['id']);
        $freeCars = $carService->prepareTeamCars($user['Team']['id']);
        $form = $this->getForm('team','tactics');

        $driver1_id = $form->getElement('driver1_id');
        $driver1_id->addMultiOptions($teamDrivers,true);
        $driver1_id->setValue($user['Team']['driver1_id']);
        $driver2_id = $form->getElement('driver2_id');
        $driver2_id->addMultiOptions($teamDrivers,true);
        $driver2_id->setValue($user['Team']['driver2_id']);
        $pilot1_id = $form->getElement('pilot1_id');
        $pilot1_id->addMultiOptions($teamPilots,true);
        $pilot1_id->setValue($user['Team']['pilot1_id']);
        $pilot2_id = $form->getElement('pilot2_id');
        $pilot2_id->addMultiOptions($teamPilots,true);
        $pilot2_id->setValue($user['Team']['pilot2_id']);
        $car1_id = $form->getElement('car1_id');
        $car1_id->addMultiOptions($freeCars,true);
        $car1_id->setValue($user['Team']['car1_id']);
        $car2_id = $form->getElement('car2_id');
        $car2_id->addMultiOptions($freeCars,true);
        $car2_id->setValue($user['Team']['car2_id']);
        $form->createElement('submit','submit');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $teamService->saveTeamFromArray($values,$user['Team']['id']);
                TK_Helper::redirect('/account/tactics');
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('form',$form);
    }
    
    public function myTrainingReport(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        Service::loadModels('people', 'people');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $trainingService = parent::getService('people','training');
        $results = $trainingService->getLastWeekTrainingResults($user['Team']['id']);
        $this->view->assign('results',$results);
    }
    
    public function myFinances(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        Service::loadModels('people', 'people');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $teamService = parent::getService('team','team');
        $financialReportSimple = $teamService->getSimpleReport($user['Team']['id']);
        $financialReportSimpleLastWeek = $teamService->getSimpleReport($user['Team']['id'],1);
        $financialReportAdvanced = $teamService->getAdvancedReport($user['Team']['id']);
        $this->view->assign('team',$user['Team']);
        $this->view->assign('financialReportSimple',$financialReportSimple);
        $this->view->assign('financialReportSimpleLastWeek',$financialReportSimpleLastWeek);
        $this->view->assign('financialReportAdvanced',$financialReportAdvanced);
        
        $this->getLayout()->setLayout('page');
    }
    
    public function sponsor(){
        
        Service::loadModels('team', 'team');
        
        $sponsorService = parent::getService('team','sponsor');
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $team = $teamService->getTeam($user['Team']['id']);
        
        $sponsors = $sponsorService->getAllSponsorList($user['Team']['sponsor_id']);
        
        
        if(isset($_POST['sponsor_id'])&&strlen($_POST['sponsor_id'])>0){
            $team->set('sponsor_id',$_POST['sponsor_id']);
            $team->save();
            TK_Helper::redirect('/account/sponsor/');
        }
        $this->view->assign('user',$user);
        $this->view->assign('sponsors',$sponsors);
    }
    
    public function myPeople(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');   
        
        $peopleService = parent::getService('people','people');
        $userService = parent::getService('user','user');
        
        
	$form = $this->getForm('market','offer');
        $this->view->assign('form',$form);
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $teamPeople = $peopleService->getTeamPeopleByRole($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        $this->view->assign('teamPeople',$teamPeople);
    }
    
    public function myCars(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
	
        $carService = parent::getService('car','car');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	
        $cars = $carService->getTeamCars($user['Team']['id']);
        
        
	$form = $this->getForm('market','offer');
        
//	$formCar1 = new Form();
//	
//        $formCar1->createElement('text','car1_name',array('validators' => 'alphanum'),'Nowa nazwa(dozwolona 1 zmiana na miesiąc)');
//        $formCar1->createElement('submit','submit_car1',array(),'Change');
//	
//	if($formCar1->isSubmit()){
//            if($formCar1->isValid()){
//                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
//                
//                $values = $_POST;
//		
//		$last_name_change_date = $user['Team']['Car1']['last_name_change'];
//		if($last_name_change_date!= "0000-00-00 00:00:00"){
//		    
//		    // last name change date
//		    $date1 = new DateTime($last_name_change_date);
//		    
//		    // now date
//		    $date2 = new DateTime();
//		    
//		    // calculate diff between dates
//		    $diff = $date2->diff($date1)->format("%a");
//		    
//		    
//		    if( $diff > 30){
//			$user['Team']['Car1']['name'] = $values['car1_name'];
//			$user['Team']['Car1']['last_name_change'] = date('Y-m-d H:i:s');
//			TK_Helper::redirect('/account/my-cars/');
//		    }
//		    else{
//			$formCar1->setError('Można dokonać 1 zmiany nazwy na 30 dni');
//		    }
//		}
//		else{
//		    $user['Team']['Car1']['name'] = $values['car1_name'];
//		    $user['Team']['Car1']['last_name_change'] = date('Y-m-d H:i:s');
//		    TK_Helper::redirect('/account/my-cars/');
//		}
//		
//		
//		
//		
//		$user->save();
////		
//                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
//            }
//        }
	
	$this->view->assign('cars',$cars);
	$this->view->assign('form',$form);
//	$this->view->assign('formCar1',$formCar1);
    }
    
    public function premium(){
        
        require_once(BASE_PATH."/library/Zend/Locale.php");
        require_once(BASE_PATH."/library/Zend/Currency.php");
        $currency = new Zend_Currency();
        $url = "https://currency-api.appspot.com/api/GBP/".$currency->getShortName().".json?amount=2.00";

        $result = file_get_contents($url);
        $rateRow = json_decode($result,true);
        
        Service::loadModels('team', 'team');
	$form = $this->getForm('user','premium');
	
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        if(strpos($_SERVER['REQUEST_URI'],'account/premium?')!==false){
            $userService->refreshAuthentication();
        }
        
//	if($form->isSubmit()){
//            if($form->isValid()){
//                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
//                
//                $values = $_POST;
//                $result = $userService->addPremium($user,$values['premium']);
//		
//                if($result!== false){
//                    TK_Helper::redirect('/account/premium?msg=success&amount='.$values['premium']);
//                }
//                else{
//                    $this->view->assign('message','Error');
//                }
//                
//                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
//            }
//        }
	
	$this->view->assign('form',$form);
	$this->view->assign('currency',$currency);
	$this->view->assign('rateRow',$rateRow);
        

        
    }
    
    public function goldMembership(){
        Service::loadModels('team', 'team');
	$form = $this->getForm('user','gold');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	
	if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                $gold = (int)$values['gold'];
                if(!(User_Model_Doctrine_User::getGoldPackagePrice($gold)&&$userService->checkUserPremium($user['id'],User_Model_Doctrine_User::getGoldPackagePrice($gold)))){
                    TK_Helper::redirect('/account/gold-membership?msg=no+prem');
                    exit;
                }
                $result = $userService->addGoldMembership($user,$gold);
                $userService->removePremium($user,User_Model_Doctrine_User::getGoldPackagePrice($gold),$gold.' days gold membership');
		
                if($result!== false)
                    TK_Helper::redirect('/account/gold-membership?msg=bought');
                else{
                    $this->view->assign('message','Error');
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
	
	$this->view->assign('form',$form);
    }
    
    
    public function manual(){
        $this->getLayout()->setLayout('page');
        
        
        $subpage = $GLOBALS['urlParams'][1];
        if(!isset($_COOKIE['lang'])||$_COOKIE['lang']=='gb'){
            if(!file_exists(BASE_PATH."/modules/index/views/manual/".$subpage.".phtml")){
                throw new TK_Exception('Page not exists',404);
                exit;
            }
        }
        else{
            if(!file_exists(BASE_PATH."/modules/index/views/manual-pl/".$subpage.".phtml")){
                throw new TK_Exception('Page not exists',404);
                exit;
            }
        }
        
        $this->view->assign('subpage',$subpage);
    }
    
}
?>
