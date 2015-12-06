<?php

class Car_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function createRandomCar(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
	
        $carService = parent::getService('car','car');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	
	$carModel = $carService->getRandomLeagueCar($user['Team']['league_id']);
	$car = $carService->createNewTeamCar($carModel);
	
	$user['Team']['Car1'] = $car;
	$user->save();
	
        TK_Helper::redirect('/index/index');
    }
    
    public function sellCar(){
        Service::loadModels('market', 'market');
        Service::loadModels('car', 'car');
        Service::loadModels('rally', 'rally');
        $marketService = parent::getService('market','market');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        
//        $id = $GLOBALS['urlParams']['id'];
        $id = $_POST['car_id'];
        $car = $carService->getCar($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        
	$form = $this->getForm('market','offer');
        $user = $userService->getAuthenticatedUser();
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                $values = $_POST;
                
                if($carService->carInRally($car)){
                    TK_Helper::redirect('/account/my-cars?msg=in+rally');
                    exit;
                }
                
                $values['team_id'] = $user['Team']['id'];
                
                $result = $marketService->addCarOnMarket($values,$car);
                
                if($result['status']!== false){
                    if(isset($_COOKIE['car_seller'])){
                        $car_seller = unserialize($_COOKIE['car_seller']);
                    }
                    $car_seller[$car['id']] = $user['id'];
                    setcookie('car_seller',serialize($car_seller),time()+(86400 * 4),'/');
                    TK_Helper::redirect('/market/show-car-offer/'.$result['element']['id']);
                }
                else{
                    TK_Helper::redirect('/account/my-cars?msg='.$result['message']);
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
            
            else{
                    TK_Helper::redirect('/account/my-people');
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('car',$car);
    }
    
    public function changeName(){
        $this->getLayout()->setLayout('page');
        
        Service::loadModels('rally', 'rally');
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
	
        $carService = parent::getService('car','car');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $id = $GLOBALS['urlParams'][1];
//        $id = $_POST['car_id'];
        $car = $carService->getCar($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        
        if($car['team_id']!=$user['Team']['id'])
            TK_Helper::redirect('/account/my-cars');
                
	$form = $this->getForm('car','NameChange');
        
        $form->populate($car->toArray());
	
        // gold members can change car name every week, non gold members every 30 days
        if($user['gold_member']){
            $allowedDiff = 7;
        }
        else{
            $allowedDiff = 30;
        }
        
        $last_name_change_date = $car['last_name_change'];
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
        
                
	if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                $values = $_POST;
		
		$last_name_change_date = $car['last_name_change'];
		if($last_name_change_date!= "0000-00-00 00:00:00" && $last_name_change_date!= null){
		    
		    // last name change date
		    $date1 = new DateTime($last_name_change_date);
		    
		    // now date
		    $date2 = new DateTime();
		    
		    // calculate diff between dates
		    $diff = $date2->diff($date1)->format("%a");
		    
		    
		    if( $diff >= $allowedDiff){
			$car->set('name',$values['name']);
			$car->set('last_name_change',date('Y-m-d H:i:s'));
                        $car->save();
			TK_Helper::redirect('/account/my-cars/');
		    }
		    else{
			$form->setError('Można dokonać 1 zmiany nazwy na 30 dni');
		    }
		}
		else{
                    $car->set('name',$values['name']);
                    $car->set('last_name_change',date('Y-m-d H:i:s'));
                    $car->save();
		    TK_Helper::redirect('/account/my-cars?msg=name+changed');
		}
		
		
		
		
		$user->save();
//		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
	
	$this->view->assign('nextDateChange',$nextDateChange);
	$this->view->assign('car',$car);
	$this->view->assign('form',$form);
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zmień nazwe samochodu - FastRally');
        }
        else{
            $this->view->setHeadTitle('Change car name - FastRally');
        }
    }
    
    public function scrapCar(){
        
        $view = $this->view;
        $view->setNoRender();
         
        Service::loadModels('market', 'market');
        Service::loadModels('car', 'car');
        Service::loadModels('rally', 'rally');
        $marketService = parent::getService('market','market');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $id = $GLOBALS['urlParams'][1];
        if(!$car = $carService->getCar($id,'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such car',404);
            exit;
        }
        
        if($user['Team']['id']!=$car['team_id']){
            throw new TK_Exception('Page not exists',404);
            exit;
        }
        
        
        if($carService->carInRally($car)){
            TK_Helper::redirect('/account/my-cars?msg=scrap+in+rally');
            exit;
        }
        
        if($car->get('on_market')){
            TK_Helper::redirect('/account/my-cars?msg=on+market');
            exit;
        }
        
        $scrapPrice = ceil($car['value']*0.01);
        
        if(!$marketService->canAffordThis($user['Team'],$scrapPrice)){
            TK_Helper::redirect('/account/my-cars?msg=no+money');
        }
        elseif(!$marketService->canAfford($user['Team'],$scrapPrice)){
            TK_Helper::redirect('/account/my-cars?msg=not+enough+money');
        }
        else{
            $teamService->removeTeamMoney($user['Team']['id'],$scrapPrice,9,'Scapped car '.$car['name']);
            $car->delete();
            TK_Helper::redirect('/account/my-cars?msg=car+scrapped');
        }
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zezłomuj samochód - FastRally');
        }
        else{
            $this->view->setHeadTitle('Scrap car - FastRally');
        }
    }
}
?>
