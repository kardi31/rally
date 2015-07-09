<?php

class People_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function createRandomPeople(){
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $data = array();
        $data['driver1_id'] = $peopleService->createRandomDriver(5)->get('id');
        $data['driver2_id'] = $peopleService->createRandomDriver(5)->get('id');
        $data['pilot1_id'] = $peopleService->createRandomPilot(5)->get('id');
        $data['pilot2_id'] = $peopleService->createRandomPilot(5)->get('id');
        
        $teamService->createRandomTeam($data);
    }
    
    public function setActiveTrainingSkill(){
        Service::loadModels('market', 'market');
        Service::loadModels('rally', 'crew');
        
        $marketService = parent::getService('market','market');
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        
        $id = $GLOBALS['urlParams']['id'];
        $skill = $GLOBALS['urlParams']['skill'];
        $player = $peopleService->getPerson($id,'id',Doctrine_Core::HYDRATE_RECORD);
        $player->set('active_training_skill',$skill);
        $player->save();
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
	
    }
    
    public function sellPlayer(){
        Service::loadModels('market', 'market');
        Service::loadModels('rally', 'crew');
        
        $marketService = parent::getService('market','market');
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $userService = parent::getService('user','user');
        
        $id = $_POST['player_id'];
        $player = $peopleService->getPerson($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
	$form = $this->getForm('market','offer');
        
        $user = $userService->getAuthenticatedUser();
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                $values['team_id'] = $user['Team']['id'];
                $result = $marketService->addPlayerOnMarket($values,$player);
                
                
                if($result!== false){
                    if(isset($_COOKIE['player_seller'])){
                        $player_seller = unserialize($_COOKIE['player_seller']);
                    }
                    $player_seller[$player['id']] = $user['id'];
                    setcookie('player_seller',serialize($player_seller),time()+(86400 * 4),'/');
                    TK_Helper::redirect('/market/show-offer/id/'.$result['element']['id']);
                }
                else{
                    TK_Helper::redirect('/account/my-people?msg='.$result['message']);
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('player',$player);
    }
    
    
}
?>
