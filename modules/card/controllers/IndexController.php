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
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
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
    
    public function showCategory(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
	
        $cardService = parent::getService('card','card');
        if(!$category = $cardService->getCategory($GLOBALS['urlParams'][1],'slug',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such category',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $threads = $cardService->getAllCategoryThreads($category['id'],Doctrine_Core::HYDRATE_RECORD);
        $form = $this->getForm('card','thread');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
                // user can add a post once every 30 seconds
                if(!$cardService->checkLastUserThread($user)){
                    TK_Helper::redirect('/card/show-category/'.$category['slug'].'?msg=too+fast');
                    exit;
                }
                
		$cardService->addThread($values,$category['id'],$user);
		
		TK_Helper::redirect('/card/show-category/'.$category['slug']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('category',$category);
	$this->view->assign('threads',$threads);
    }
    
    public function showThread(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
	
        $cardService = parent::getService('card','card');
        if(!$thread = $cardService->getFullThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such thread',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('card','post');
        
        $posts = $cardService->getAllThreadPosts($thread['id'],Doctrine_Core::HYDRATE_ARRAY);
       
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                
                // user can add a post once every 30 seconds
                if(!$cardService->checkLastUserPost($user,$thread)){
                    TK_Helper::redirect('/card/show-thread/'.$thread['id'].'?msg=too+fast');
                    exit;
                }
                
                $values = $_POST;
		
		$cardService->addPost($values,$thread,$user);
		TK_Helper::redirect('/card/show-thread/'.$thread['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('user',$user);
	$this->view->assign('form',$form);
	$this->view->assign('posts',$posts);
	$this->view->assign('thread',$thread);
    }
    
    public function editThread(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $cardService = parent::getService('card','card');
        if(!$thread = $cardService->getThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such thread',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
       
        $form = $this->getForm('card','thread');
        $form->populate($thread);
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                $values = $_POST;
                
                if(in_array($user['role'],array('moderator','admin'))){
                    $values['moderator_date'] = date('Y-m-d H:i:s');
                    $values['moderator_name'] = $user['username'];
                }
		$values['id'] = $thread['id'];
		$thread = $cardService->editThread($values);
		
		TK_Helper::redirect('/card/show-thread/'.$thread['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('user',$user);
	$this->view->assign('thread',$thread);
    }
    
    public function deleteThread(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $cardService = parent::getService('card','card');
        if(!$thread = $cardService->getThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such thread',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
       
        $form = $this->getForm('card','thread');
        $form->populate($thread);
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                $values = $_POST;
                
                if(in_array($user['role'],array('moderator','admin'))){
                    $values['moderator_date'] = date('Y-m-d H:i:s');
                    $values['moderator_name'] = $user['username'];
                    $values['moderator_comment'] = $user['username'];
                    $values['id'] = $thread['id'];
                    $thread = $cardService->editThread($values);
                    $thread->delete();
                }
		
		TK_Helper::redirect('/card/show-category/'.$thread['Category']['slug']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('user',$user);
	$this->view->assign('thread',$thread);
    }
    
    public function setThreadActive(){
        $this->view->setNoRender();
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $cardService = parent::getService('card','card');
        if(!$thread = $cardService->getThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such thread',404);
        }
        
       
        if($thread->get('active')){
            $thread->set('active',0);
        }
        else{
            $thread->set('active',1);
        }
            
        $thread->save();
        
	TK_Helper::redirect('/card/show-category/'.$thread['Category']['slug']);
	     
    }
    
    public function editPost(){
        Service::loadModels('card', 'card');
	
        $cardService = parent::getService('card','card');
        if(!$post = $cardService->getPost($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such post',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('card','post');
        $form->populate($post->toArray());
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
                if(in_array($user['role'],array('moderator','admin'))){
                    $values['moderator_date'] = date('Y-m-d H:i:s');
                    $values['moderator_name'] = $user['username'];
                }
                $values['id'] = $post['id'];
		$cardService->editPost($values);
		
		TK_Helper::redirect('/card/show-thread/'.$post['Thread']['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('user',$user);
	$this->view->assign('form',$form);
	$this->view->assign('post',$post);
    }
    
    public function deletePost(){
        Service::loadModels('card', 'card');
	
        $cardService = parent::getService('card','card');
        if(!$post = $cardService->getPost($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such post',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('card','post');
        $form->populate($post->toArray());
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
                if(in_array($user['role'],array('moderator','admin'))){
                    $values['moderator_date'] = date('Y-m-d H:i:s');
                    $values['moderator_name'] = $user['username'];
                }
                $values['id'] = $post['id'];
		$post = $cardService->editPost($values);
		$post->delete();
		TK_Helper::redirect('/card/show-thread/'.$post['Thread']['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('user',$user);
	$this->view->assign('form',$form);
	$this->view->assign('post',$post);
    }
    
    public function addFavouriteCard(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $cardService = parent::getService('card','card');
        if(!$category = $cardService->getCategory($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such card',404);
        }
        
        if(!($user['gold_member']&&$user['gold_member_expire']>date('Y-m-d H:i:s'))){
            throw new TK_Exception('You are not a gold member',404);
        }
        
        $cardService->addCategoryToFavourite($category,$user);
        
        
        TK_Helper::redirect('/card/show-card');
        
    }
    
    public function removeFavouriteCard(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $cardService = parent::getService('card','card');
        
        
        if(!$category = $cardService->getCategory($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such card',404);
        }
        
        if(!($user['gold_member']&&$user['gold_member_expire']>date('Y-m-d H:i:s'))){
            throw new TK_Exception('You are not a gold member',404);
        }
        
        $cardService->removeCategoryFromFavourite($category['id'],$user['id']);
        
        
        TK_Helper::redirect('/card/show-card');
    }
    
    public function showFavouriteCards(){
        Service::loadModels('card', 'card');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $cardService = parent::getService('card','card');
        
        $favouriteCategories = $cardService->getFavouriteCategories($user['id'],true,Doctrine_Core::HYDRATE_ARRAY);
        $lastThreads = array();
        foreach($favouriteCategories as $favourite):
            $lastThread = $cardService->getLastCategoryThread($favourite['category_id'],Doctrine_Core::HYDRATE_ARRAY);
            $lastPost = $cardService->getLastCategoryPost($favourite['category_id'],Doctrine_Core::HYDRATE_ARRAY);

            if(isset($lastThread['created_at'])&&$lastThread['created_at']>$lastPost['created_at']){
                $lastThreads[$favourite['category_id']] = $lastThread;
            }
            else{
                $lastThreads[$favourite['category_id']] = $lastPost;
            }
        endforeach;
        $this->view->assign('favouriteCategories',$favouriteCategories);
        $this->view->assign('lastThreads',$lastThreads);
        
        $this->getLayout()->setLayout('layout');
    }
    
}
?>
