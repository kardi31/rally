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
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Rynek zawodników - FastRally');
        }
        else{
            $this->view->setHeadTitle('Players market - FastRally');
        }
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
                
                if($offer['finish_date']>date('Y-m-d H:i:s')){
                    TK_Helper::redirect('/market/show-market/?msg=offer+expired');
                }
                
                $result = $marketService->bidOffer($values,$offer,$user['Team']);
                if($result['status']!== false){
                    if(isset($_COOKIE['player_seller'])){
                        $player_seller = unserialize($_COOKIE['player_seller']);
                        if(isset($_COOKIE['player_seller'][$offer['id']])){
                            $duplicateService->savePeopleDuplicate($offer['id'],$result['element']['id']);
                        }
                    }
                    
                    TK_Helper::redirect('/market/show-offer/'.$offer['id'].'?msg=bid+placed');
                }
                else{
                    TK_Helper::redirect('/market/show-offer/'.$offer['id'].'?msg='.urlencode($result['message']));
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
        
        $id = $GLOBALS['urlParams'][1];
        if(!$offer = $marketService->getFullOffer($id,'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('Offer not exists',404);
        }
        
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
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle($offer['Player']['firstname']." ".$offer['Player']['lastname'].' na rynku transferowym - FastRally');
        }
        else{
            $this->view->setHeadTitle($offer['Player']['firstname']." ".$offer['Player']['lastname'].' on transfer market - FastRally');
        }
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
              
        
        usort($offers,function ($a, $b)
        {
            return ($a["finish_date"] < $b["finish_date"])?-1:1;
        });
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$offers);
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Monitor zawodników na rynku transferowym - FastRally');
        }
        else{
            $this->view->setHeadTitle('Player monitor on transfer market - FastRally');
        }
    }
    
    
    
    /*
     * End of player part
     * 
     * 
     * Start of car part
     */
    
    public function carDealer(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $carService = parent::getService('car','car');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $carsForSale = $carService->getCarsForSale(Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('carsForSale',$carsForSale);
        $this->view->assign('user',$user);
        $this->getLayout()->setLayout('page');
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Dealer samochodowy - FastRally');
        }
        else{
            $this->view->setHeadTitle('Car dealer - FastRally');
        }
    }
    
    
    public function freeAgency(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('rally', 'rally');
        $marketService = parent::getService('market','market');
        $userService = parent::getService('user','user');
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        parent::getService('people','training');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $form = $this->getForm('market','freeAgent');
        $this->view->assign('form',$form);
        
        $league_level = (int)$user['Team']['league_name'];
        $freeAgencyPrice = (7-$league_level)*5000;
        
        if($form->isSubmit()){
            if($form->isValid()){                
                $values = $_POST;
                
                
                if(!$marketService->canAffordThis($user['Team'],$freeAgencyPrice)){
                    TK_Helper::redirect('/market/free-agency?msg=no+money');
                }
                elseif(!$marketService->canAfford($user['Team'],$freeAgencyPrice)){
                    TK_Helper::redirect('/market/free-agency?msg=not+enough+money');
                }
                else{
                    Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                    $league_level = rand(1,5);
                    
                    if($values['job']=='driver')
                        $person = $peopleService->createRandomDriver($league_level,$user['Team']['id']);
                    elseif($values['job']=='pilot')
                        $person = $peopleService->createRandomPilot($league_level,$user['Team']['id']);
                    
                    
                    $teamService->removeTeamMoney($user['Team']['id'],$freeAgencyPrice,9,'Free agent player '.$person['first_name']." ".$person['last_name']." has been acquired.");    
                    
                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();           
                    TK_Helper::redirect('/account/my-people?msg=free+agent+acquired');
                }
                
            }
        }
        
        $this->getLayout()->setLayout('page');
        $this->view->assign('freeAgencyPrice',$freeAgencyPrice);
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Wolni zawodnicy - FastRally');
        }
        else{
            $this->view->setHeadTitle('Free agency - FastRally');
        }
    }
    
    public function buyCar(){
        
        Service::loadModels('team', 'team');
        Service::loadModels('car', 'car');
        $carService = parent::getService('car','car');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        
        $id = $GLOBALS['urlParams'][1];
        if(!$carModel = $carService->getCarModel($id,'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('Car not exists',404);
        }
        
        if(!$carModel['on_market']){
            throw new TK_Exception('Hacking attempt',404);
        }
        
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
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Rynek samochodowy - FastRally');
        }
        else{
            $this->view->setHeadTitle('Car market - FastRally');
        }
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
                
                if($offer['finish_date']>date('Y-m-d H:i:s')){
                    TK_Helper::redirect('/market/show-car-market/?msg=offer+expired');
                }
                                
                $result = $marketService->bidCarOffer($values,$offer,$user['Team']);
                if($result['status']!== false){
                    if(isset($_COOKIE['car_seller'])){
                        $car_seller = unserialize($_COOKIE['car_seller']);
                        if(isset($_COOKIE['car_seller'][$offer['id']])){
                            $duplicateService->saveCarDuplicate($offer['id'],$result['element']['id']);
                        }
                    }
                    TK_Helper::redirect('/market/show-car-offer/'.$offer['id'].'?msg=bid+placed');
                }
                else{
                    TK_Helper::redirect('/market/show-car-offer/'.$offer['id'].'?msg='.urlencode($result['message']));
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
        
        $id = $GLOBALS['urlParams'][1];
        if(!$offer = $marketService->getFullCarOffer($id,'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('Offer not exists',404);
        }
        
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
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle($offer['Car']['name'].' na rynku transferowym - FastRally');
        }
        else{
            $this->view->setHeadTitle($offer['Car']['name'].' on car market - FastRally');
        }
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
        
        $myCars = $marketService->getAllActiveMyCars($user['Team']['id']);
        
        $marketOffers = array_merge($marketOffers,$myCars);
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$marketOffers);
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Monitor samochodów na rynku transferowym - FastRally');
        }
        else{
            $this->view->setHeadTitle('Car monitor - FastRally');
        }
    }
    
    public function showMyPlayerOffers(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $marketService = parent::getService('market','market');
        $teamService = parent::getService('team','team');
        $data = array();
        $marketOffers = $marketService->getAllActiveMyOffers($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        
        $myPlayers = $marketService->getAllActiveMyPlayers($user['Team']['id'],Doctrine_Core::HYDRATE_ARRAY);
        $marketOffers = array_merge($myPlayers,$marketOffers);
        
        $carMarketOffers = $marketService->getAllActiveMyCarOffers($user['Team']['id']);
        
        $myCars = $marketService->getAllActiveMyCars($user['Team']['id']);
        
        $carMarketOffers = array_merge($carMarketOffers,$myCars);
        
        $allOffers = array_merge($marketOffers,$carMarketOffers);    
        
        function ordbydate2($a, $b)
        {
            return ($a["finish_date"] < $b["finish_date"])?-1:1;
        }  
        
        usort($allOffers,'ordbydate2');
        
        
        $this->view->assign('user',$user);
        $this->view->assign('marketOffers',$allOffers);
        
        
        $this->getLayout()->setLayout('layout');
    }
    
}
?>
