<?php

class Card_Index extends Controller{
 
    private static $instance = NULL;
    
    public function __construct(){
        parent::__construct();
        $this->getLayout()->setLayout('page');
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        
        $this->getLayout()->setLayout('fullpage');
        
        $cardService = parent::getService('card','card');
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $userCards = json_encode($cardService->getUserCards($user['id']));
        $this->view->assign('userCards',$userCards);
        
    }
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new Card_Index();
       return self::$instance;
    }
    
    public function showCard(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
	
        $cardService = parent::getService('card','card');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $categories = $cardService->getAllCategories(Doctrine_Core::HYDRATE_ARRAY);
        $favouriteCategories = $cardService->getFavouriteCategories($user['id'],false,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        
        foreach($categories as $key=>$category):
            $lastPost = $cardService->getLastCategoryPost($category['id'],Doctrine_Core::HYDRATE_ARRAY);
            $lastThread = $cardService->getLastCategoryThread($category['id'],Doctrine_Core::HYDRATE_ARRAY);
            if($lastPost['created_at']>$lastThread['created_at']){
                $categories[$key]['last_post'] = $lastPost;
            }
            else{
                $categories[$key]['last_post'] = $lastThread;
            }
            $categories[$key]['thread_count'] = $cardService->countCategoryThreads($category['id']);
            $categories[$key]['post_count'] = $cardService->countCategoryPosts($category['id']);
        endforeach;
        
	$this->view->assign('favouriteCategories',$favouriteCategories);
	$this->view->assign('categories',$categories);
    }
    
    public function manageCards(){
        Service::loadModels('card', 'card');
        Service::loadModels('car', 'car');
        Service::loadModels('user', 'user');
	
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $cardService = parent::getService('card','card');
        $unlockedCards = $cardService->getUnlockedUserCards($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        $lockedCards = $cardService->getLockedUserCards($user['id'],Doctrine_Core::HYDRATE_ARRAY);
	$this->view->assign('lockedCards',$lockedCards);
	$this->view->assign('unlockedCards',$unlockedCards);
        
        $lockedSameType = $cardService->getLockedUserCardsSameType($user['id'],Doctrine_Core::HYDRATE_ARRAY);
	$this->view->assign('lockedSameType',$lockedSameType);
    }
    
    public function transformCards(){
        Service::loadModels('card', 'card');
        Service::loadModels('car', 'car');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $cardService = parent::getService('card','card');
        
        $carService = parent::getService('car','car');
        
        $lockedSameType = $cardService->checkLockedUserCardsSameType($user['id'],$GLOBALS['urlParams'][1],Doctrine_Core::HYDRATE_ARRAY);
        
        if($lockedSameType){
            
            $carService->createNewTeamCar($lockedSameType['Model']['id'],$user['Team']['id']);
            
            $cardsToTransform = $cardService->getCardsToTransform($user['id'],$lockedSameType['Model']['id']);
            foreach($cardsToTransform as $card):
                $card->delete();
            endforeach;
//            
            $cardService->createRandomCards($user['id'],7);
            
            
            TK_Helper::redirect('/account/my-cars?msg=transformed&&car='.urlencode($lockedSameType['Model']['name']));
            
        }
        else{
            TK_Helper::redirect('/card/manage-cards?msg=not+enough+locked');
        }
    }
    
    public function lockCard(){
        Service::loadModels('card', 'card');
        Service::loadModels('car', 'car');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $view= $this->view;
        $view->setNoRender();
        
        $cardService = parent::getService('card','card');
        $card = $cardService->getCard($_POST['id']);
        
        if($card->get('user_id')==$user['id']){
            if($card->get('locked')){
                $card->set('locked',0);
                $result['msg'] = 'unlocked';
            }
            else{
                $countLockedCards = $cardService->countLockedCards($user['id']);
                if($user->isGoldMember()){
                    if($countLockedCards>9){
                        $result['error'] = 'Too many locked cards';
                    }
                }
                else{
                    if($countLockedCards>7){
                        $result['error'] = 'Too many locked cards';
                    }
                }
                if(!isset($result['error'])){
                        $result['msg'] = 'locked';
                        $card->set('locked',1);
                }
            }
            $card->save();

            echo json_encode($result);
        }
        
        
        
        
        
    }
    
}
?>
