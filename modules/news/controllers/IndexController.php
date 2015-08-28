<?php

class News_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function createRandomNews(){
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
	
        $newsService = parent::getService('news','news');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
	
	$newsModel = $newsService->getRandomLeagueNews($user['Team']['league_id']);
	$news = $newsService->createNewTeamNews($newsModel);
	
	$user['Team']['News1'] = $news;
	$user->save();
	
        TK_Helper::redirect('/index/index');
    }
    
    public function sellNews(){
        Service::loadModels('market', 'market');
        Service::loadModels('news', 'news');
        Service::loadModels('rally', 'rally');
        
        $marketService = parent::getService('market','market');
        $newsService = parent::getService('news','news');
        $teamService = parent::getService('team','team');
        
        $id = $GLOBALS['urlParams'][1];
        $news = $newsService->getNews($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        
	$form = $this->getForm('market','offer');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $result = $marketService->addNewsOnMarket($values,$news);
                
                if($result!== false)
                    TK_Helper::redirect('/account/my-newss');
                else{
                    $this->view->assign('message','Player already on market');
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('news',$news);
    }
    
    
    
}
?>
