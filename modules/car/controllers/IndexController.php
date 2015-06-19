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
        
        $id = $GLOBALS['urlParams']['id'];
        $car = $carService->getCar($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        
	$form = $this->getForm('market','offer');
        $user = $userService->getAuthenticatedUser();
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                $values['team_id'] = $user['Team']['id'];
                
                $result = $marketService->addCarOnMarket($values,$car);
                
                if($result!== false){
                    if(isset($_COOKIE['car_seller'])){
                        $car_seller = unserialize($_COOKIE['car_seller']);
                    }
                    $car_seller[$car['id']] = $user['id'];
                    setcookie('car_seller',serialize($car_seller),time()+(86400 * 4),'/');
                    TK_Helper::redirect('/account/my-cars');
                }
                else{
                    $this->view->assign('message','Player already on market');
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('car',$car);
    }
    
    
    
}
?>
