<?php

class League_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
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
    
    
    
}
?>
