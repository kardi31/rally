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
        $carService = parent::getService('car','car');
        
        $cardService = parent::getService('card','card');
        $unlockedCards = $cardService->getUnlockedUserCards($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        $lockedCards = $cardService->getLockedUserCards($user['id'],Doctrine_Core::HYDRATE_ARRAY);
	$this->view->assign('lockedCards',$lockedCards);
	$this->view->assign('unlockedCards',$unlockedCards);
        
        $lockedSameType = $cardService->getLockedUserCardsSameType($user['id'],Doctrine_Core::HYDRATE_ARRAY);
	$this->view->assign('lockedSameType',$lockedSameType);
        
        if(isset($_GET['package'])){
            $package = $cardService->getPackage((int)$_GET['package'],$user['id']);
            if($package){
                $boughtPackageCars = $carService->getMultipleCarModels($package['model_ids']);
                $this->view->assign('boughtPackageCars',$boughtPackageCars);
            }
        }
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
    
    public function buyCards(){
        Service::loadModels('card', 'card');
        Service::loadModels('car', 'car');
        Service::loadModels('user', 'user');

        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();

        $cardService = parent::getService('card','card');
        $carService =  parent::getService('car','car');
        $unlockedCards = $cardService->getUnlockedUserCards($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        $lockedCards = $cardService->getLockedUserCards($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        $this->view->assign('lockedCards',$lockedCards);
        $this->view->assign('unlockedCards',$unlockedCards);

        $lockedSameType = $cardService->getLockedUserCardsSameType($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        $this->view->assign('lockedSameType',$lockedSameType);
        
        
    }
    
    public function buyPackage(){
        if(isset($GLOBALS['urlParams'][1])){
            Service::loadModels('user', 'user');

            $userService = parent::getService('user','user');
            $user = $userService->getAuthenticatedUser();
            $amount = (int)$GLOBALS['urlParams'][1];
            $cardService = parent::getService('card','card');
            parent::getService('car','car');
            
            if($user['gold_member']){
                $cardPrice = $cardService->checkCardPrice($amount,true);
            }
            else{
                $cardPrice = $cardService->checkCardPrice($amount);
            }
            
            if(!$cardPrice){
                TK_Helper::redirect('/card/buy-cards?msg=wrong+package');
                exit;
            }

            if(!$userService->checkUserPremium($user['id'],$cardPrice)){
                TK_Helper::redirect('/card/buy-cards?msg=not+enough+premium');
                exit;
            }
            else{

                $package = $cardService->buyPackage($user['id'],$amount);

                $userService->removePremium($user,$cardPrice,'Bought package of '.$amount.' playing cards.');
                                 
                TK_Helper::redirect('/card/manage-cards?msg=cards+added&&package='.$package->get('id'));
                exit;
            }
            
        }
        
        TK_Helper::redirect('/card/buy-cards/?msg=wrong+page');
        exit;
    }
}
?>
