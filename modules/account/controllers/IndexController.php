<?php

class Account_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
   
    
    public function myAccount(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $this->view->assign('user',$user);
        
    }
    
    public function myTeam(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        Service::loadModels('people', 'people');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $this->view->assign('team',$user['Team']);
    }
    
    public function myDrivers(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
    }
    
    public function myCars(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
	
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	
	$formCar1 = new Form();
	
        $formCar1->createElement('text','car1_name',array('validators' => 'alnum'),'Nowa nazwa(dozwolona 1 zmiana na miesiąc)');
        $formCar1->createElement('submit','submit_car1',array(),'Change');
	
	if($formCar1->isSubmit()){
            if($formCar1->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
		$last_name_change_date = $user['Team']['Car1']['last_name_change'];
		if($last_name_change_date!= "0000-00-00 00:00:00"){
		    
		    // last name change date
		    $date1 = new DateTime($last_name_change_date);
		    
		    // now date
		    $date2 = new DateTime();
		    
		    // calculate diff between dates
		    $diff = $date2->diff($date1)->format("%a");
		    
		    
		    if( $diff > 30){
			$user['Team']['Car1']['name'] = $values['car1_name'];
			$user['Team']['Car1']['last_name_change'] = date('Y-m-d H:i:s');
			TK_Helper::redirect('/account/my-cars/');
		    }
		    else{
			$formCar1->setError('Można dokonać 1 zmiany nazwy na 30 dni');
		    }
		}
		else{
		    $user['Team']['Car1']['name'] = $values['car1_name'];
		    $user['Team']['Car1']['last_name_change'] = date('Y-m-d H:i:s');
		    TK_Helper::redirect('/account/my-cars/');
		}
		
		
		
		
		$user->save();
//		$rallyService->saveRallyCrew($values,$rally,$user['Team']);
//		
//		TK_Helper::redirect('/rally/show-rally/name/'.$rally['slug']);
//		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
	
	$this->view->assign('formCar1',$formCar1);
    }
    
}
?>
