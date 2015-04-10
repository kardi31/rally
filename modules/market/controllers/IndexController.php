<?php

class Market_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function showMarket(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $data = array();
        $marketOffers = $marketService->getAllActiveOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function bidPlayer(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams']['id'];
        $offer = $marketService->getOffer($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $form = new Form();
        $form->createElement('text','bid',array('validators' => array('int')),'Cena');
        $form->getElement('bid')->addParam('autocomplete','off');
        $form->createElement('submit','submit');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $team_id = $user['Team']['id'];
                
                $result = $marketService->bidOffer($values,$offer,$team_id);
                if($result!== false)
                    TK_Helper::redirect('/market/show-offer/id/'.$offer['id']);
                else{
                    $this->view->assign('message','Licytacja się skończyła już');
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        $marketOffers = $marketService->getAllActiveOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('marketOffers',$marketOffers);
        $this->view->assign('form',$form);
    }
    
    public function showOffer(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams']['id'];
        $offer = $marketService->getFullOffer($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $this->view->assign('offer',$offer);
    }
    
    public function myPlayerOffers(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $data = array();
        $marketOffers = $marketService->getAllActiveMyPlayerOffers($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    /*
     * End of player part
     * 
     * 
     * Start of car part
     */
    
    public function carDealer(){
        
        Service::loadModels('car', 'car');
        $carService = parent::getService('car','car');
        
        $carsForSale = $carService->getCarsForSale(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('carsForSale',$carsForSale);
    }
    
    public function buyCar(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        
        $id = $GLOBALS['urlParams']['id'];
        $carModel = $carService->getCarModel($id,'id',Doctrine_Core::HYDRATE_ARRAY);
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        if($teamService->canAfford($user['Team'],$carModel['price'])){
            $carService->createNewTeamCar($carModel,$user['Team']['id']);
            $teamService->saveTeamFinance($user['Team']['id'],$carModel['price'],7,false,'Car '.$carModel['name'].' was bought');
            $this->view->assign('message','car was bought');
            
            TK_Helper::redirect('/account/my-cars');
        }
        else{
            
            $this->view->assign('message','car was bought');
            TK_Helper::redirect('/market/car-dealer');
        }
        
    }
    
    public function showCarMarket(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('car', 'car');
        Service::loadModels('team', 'team');
        $marketService = parent::getService('market','market');
        $carService = parent::getService('car','car');
        $data = array();
        $marketOffers = $marketService->getAllActiveCarOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function bidCar(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams']['id'];
        $offer = $marketService->getCarOffer($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $form = $this->getForm('market','bid');
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $team_id = $user['Team']['id'];
                
                $result = $marketService->bidCarOffer($values,$offer,$team_id);
                if($result!== false)
                    TK_Helper::redirect('/market/show-car-offer/id/'.$offer['id']);
                else{
                    $this->view->assign('message','Licytacja się skończyła już');
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        $marketOffers = $marketService->getAllActiveCarOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('marketOffers',$marketOffers);
        $this->view->assign('form',$form);
    }
    
    public function showCarOffer(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams']['id'];
        $offer = $marketService->getFullCarOffer($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $this->view->assign('offer',$offer);
        $this->view->assign('user',$user);
    }
    
    public function myCarOffers(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('car', 'car');
        Service::loadModels('team', 'team');
        $marketService = parent::getService('market','market');
        $carService = parent::getService('car','car');
        $data = array();
        $marketOffers = $marketService->getAllActiveMyCarOffers($user['Team']['id']);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
}
?>
