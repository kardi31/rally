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
        Service::loadModels('rally', 'crew');
        Service::loadModels('team', 'team');
        
        $peopleService = parent::getService('people','people');
        $userService = parent::getService('user','user');
        
        $id = $GLOBALS['urlParams'][1];
        $skill = $GLOBALS['urlParams'][2];
        
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        
        if(!$player = $peopleService->getPerson($id,'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('Player not exists',404);
        }
        
        
        if($user['Team']['id']!=$player['team_id']){
            throw new TK_Exception('This player is not in your team',404);
        }
        
        if(!in_array($skill,$peopleService->getAllPeopleSkills())){
            throw new TK_Exception('Wrong player skill chosen',404);
        }
        
        $player->set('active_training_skill',$skill);
        $player->save();
        
        
        
        TK_Helper::redirect('/account/my-people');
	
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
                
                if($peopleService->playerInRally($player)){
                    TK_Helper::redirect('/account/my-people?msg=in+rally');
                    exit;
                }
                $values = $_POST;
                $values['team_id'] = $user['Team']['id'];
                $result = $marketService->addPlayerOnMarket($values,$player);
                
                if($result['status']!== false){
                    if(isset($_COOKIE['player_seller'])){
                        $player_seller = unserialize($_COOKIE['player_seller']);
                    }
                    $player_seller[$player['id']] = $user['id'];
                    setcookie('player_seller',serialize($player_seller),time()+(86400 * 4),'/');
                    TK_Helper::redirect('/market/show-offer/'.$result['element']['id']);
                    exit;
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
