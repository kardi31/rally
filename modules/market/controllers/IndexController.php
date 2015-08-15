<?php

class Market_Index extends Controller{
    
    private static $instance = NULL;
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new Market_Index();
       return self::$instance;
    }
    public function __construct(){
        parent::__construct();
        $this->getLayout()->setLayout('page');
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
        $marketOffers = $marketService->getAllActiveOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $form = $this->getForm('market','bidPlayer');
        
        
        $this->view->assign('form',$form);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function bidPlayer(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        $duplicateService = parent::getService('market','duplicate');
                
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $form = $this->getForm('market','bidPlayer');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                if(!$offer = $marketService->getOffer($values['offer_id'],'id',Doctrine_Core::HYDRATE_RECORD)){
                    TK_Helper::redirect('/market/show-market/?msg=no+exist');
                }
                
                $result = $marketService->bidOffer($values,$offer,$user['Team']);
                if($result['status']!== false){
                    if(isset($_COOKIE['player_seller'])){
                        $player_seller = unserialize($_COOKIE['player_seller']);
                        if(isset($_COOKIE['player_seller'][$offer['id']])){
                            $duplicateService->savePeopleDuplicate($offer['id'],$result['element']['id']);
                        }
                    }
                    
                    TK_Helper::redirect('/market/show-offer/id/'.$offer['id'].'?msg=bid+placed');
                }
                else{
                    TK_Helper::redirect('/market/show-offer/id/'.$offer['id'].'?msg='.urlencode($result['message']));
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
        
        
        $form = $this->getForm('market','bidPlayer');
        $this->view->assign('form',$form);
        
        $id = $GLOBALS['urlParams']['id'];
        $offer = $marketService->getFullOffer($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        if(strlen($offer['highest_bid'])&&$offer['highest_bid']!=0){
            $form->getElement('bid')->setValue((int)($offer['highest_bid']*1.1));
        }
        else{
            $form->getElement('bid')->setValue($offer['asking_price']);
        }
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $this->view->assign('offer',$offer);
    }
    
    
    public function myPlayers(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $form = $this->getForm('market','bidPlayer');
        $this->view->assign('form',$form);
        
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $marketOffers = $marketService->getAllActiveMyPlayers($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function playerMonitor(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $form = $this->getForm('market','bidPlayer');
        $this->view->assign('form',$form);
        
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $marketOffers = $marketService->getAllActiveMyPlayerOffers($user['Team']['id']);
        $myPlayers = $marketService->getAllActiveMyPlayers($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        $offers = array_merge($myPlayers,$marketOffers);
        
        function ordbydate($a, $b)
        {
            return ($a["finish_date"] < $b["finish_date"])?-1:1;
        }        
        
        usort($offers,'ordbydate');
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$offers);
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
        $this->getLayout()->setLayout('page');
    }
    
    public function buyCar(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams']['id'];
        $carModel = $carService->getCarModel($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        if(!$marketService->canAffordThis($user['Team'],$carModel['price'])){
            TK_Helper::redirect('/market/car-dealer?msg=no+money');
        }
        elseif(!$marketService->canAfford($user['Team'],$carModel['price'])){
            TK_Helper::redirect('/market/car-dealer?msg=not+enough+money');
        }
        else{
            $carService->createNewTeamCar($carModel,$user['Team']['id']);
            $teamService->removeTeamMoney($user['Team']['id'],$carModel['price'],7,'Car '.$carModel['name'].' was bought');            
            TK_Helper::redirect('/account/my-cars?msg=car+bought');
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
        $marketOffers = $marketService->getAllActiveCarOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $form = $this->getForm('market','bid');
        $this->view->assign('form',$form);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function bidCar(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $marketService = parent::getService('market','market');
        $duplicateService = parent::getService('market','duplicate');
        
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $form = $this->getForm('market','bid');
        $this->view->assign('form',$form);
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                if(!$offer = $marketService->getCarOffer($values['offer_id'],'id',Doctrine_Core::HYDRATE_RECORD)){
                    TK_Helper::redirect('/market/show-car-market/?msg=no+exist');
                }
                
                $result = $marketService->bidCarOffer($values,$offer,$user['Team']);
                if($result['status']!== false){
                    if(isset($_COOKIE['car_seller'])){
                        $car_seller = unserialize($_COOKIE['car_seller']);
                        if(isset($_COOKIE['car_seller'][$offer['id']])){
                            $duplicateService->saveCarDuplicate($offer['id'],$result['element']['id']);
                        }
                    }
                    TK_Helper::redirect('/market/show-car-offer/id/'.$offer['id'].'?msg=bid+placed');
                }
                else{
                    TK_Helper::redirect('/market/show-car-offer/id/'.$offer['id'].'?msg='.urlencode($result['message']));
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        $marketOffers = $marketService->getAllActiveCarOffers(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function showCarOffer(){
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams']['id'];
        $offer = $marketService->getFullCarOffer($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $userService = parent::getService('user','user');
        
        $form = $this->getForm('market','bid');
        
        if(strlen($offer['highest_bid'])&&$offer['highest_bid']!=0){
            $form->getElement('bid')->setValue((int)($offer['highest_bid']*1.1));
        }
        else{
            $form->getElement('bid')->setValue($offer['asking_price']);
        }
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        $this->view->assign('offer',$offer);
        $this->view->assign('user',$user);
        $this->view->assign('form',$form);
    }
    
    public function myCars(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('car', 'car');
        Service::loadModels('team', 'team');
        $marketService = parent::getService('market','market');
        $carService = parent::getService('car','car');
        
        
        $form = $this->getForm('market','bid');
        $this->view->assign('form',$form);
        
        $marketOffers = $marketService->getAllActiveMyCars($user['Team']['id']);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function carMonitor(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('car', 'car');
        Service::loadModels('team', 'team');
        $marketService = parent::getService('market','market');
        $carService = parent::getService('car','car');
        
        
        $form = $this->getForm('market','bid');
        $this->view->assign('form',$form);
        
        $marketOffers = $marketService->getAllActiveMyCarOffers($user['Team']['id']);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
    }
    
    public function showMyPlayerOffers(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('people', 'people');
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $data = array();
        $marketOffers = $marketService->getAllActiveMyOffers($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
        
        
        $this->getLayout()->setLayout('layout');
    }
    
}
?>
