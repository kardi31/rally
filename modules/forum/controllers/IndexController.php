<?php

class Forum_Index extends Controller{
 
    private static $instance = NULL;
    
    public function __construct(){
        parent::__construct();
        $this->getLayout()->setLayout('page');
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
    }
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new Forum_Index();
       return self::$instance;
    }
    
    public function showForum(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $forumService = parent::getService('forum','forum');
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $categories = $forumService->getAllCategories(Doctrine_Core::HYDRATE_ARRAY);
        $favouriteCategories = $forumService->getFavouriteCategories($user['id'],false,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        
        foreach($categories as $key=>$category):
            $lastPost = $forumService->getLastCategoryPost($category['id'],Doctrine_Core::HYDRATE_ARRAY);
            $lastThread = $forumService->getLastCategoryThread($category['id'],Doctrine_Core::HYDRATE_ARRAY);
            if($lastPost['created_at']>$lastThread['created_at']){
                $categories[$key]['last_post'] = $lastPost;
            }
            else{
                $categories[$key]['last_post'] = $lastThread;
            }
            $categories[$key]['thread_count'] = $forumService->countCategoryThreads($category['id']);
            $categories[$key]['post_count'] = $forumService->countCategoryPosts($category['id']);
        endforeach;
        
	$this->view->assign('favouriteCategories',$favouriteCategories);
	$this->view->assign('categories',$categories);
    }
    
    public function showCategory(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $forumService = parent::getService('forum','forum');
        if(!$category = $forumService->getCategory($GLOBALS['urlParams'][1],'slug',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such category',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $threads = $forumService->getAllCategoryThreads($category['id'],Doctrine_Core::HYDRATE_RECORD);
        $form = $this->getForm('forum','thread');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
                // user can add a post once every 30 seconds
                if(!$forumService->checkLastUserThread($user)){
                    TK_Helper::redirect('/forum/show-category/'.$category['slug'].'?msg=too+fast');
                    exit;
                }
                
		$forumService->addThread($values,$category['id'],$user);
		
		TK_Helper::redirect('/forum/show-category/'.$category['slug']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('category',$category);
	$this->view->assign('threads',$threads);
    }
    
    public function showThread(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $forumService = parent::getService('forum','forum');
        if(!$thread = $forumService->getFullThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such thread',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('forum','post');
        
        $posts = $forumService->getAllThreadPosts($thread['id'],Doctrine_Core::HYDRATE_ARRAY);
       
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                
                // user can add a post once every 30 seconds
                if(!$forumService->checkLastUserPost($user,$thread)){
                    TK_Helper::redirect('/forum/show-thread/'.$thread['id'].'?msg=too+fast');
                    exit;
                }
                
                $values = $_POST;
		
		$forumService->addPost($values,$thread,$user);
		TK_Helper::redirect('/forum/show-thread/'.$thread['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('user',$user);
	$this->view->assign('form',$form);
	$this->view->assign('posts',$posts);
	$this->view->assign('thread',$thread);
    }
    
    public function editThread(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $forumService = parent::getService('forum','forum');
        if(!$thread = $forumService->getThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such thread',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
       
        $form = $this->getForm('forum','thread');
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
		$thread = $forumService->editThread($values);
		
		TK_Helper::redirect('/forum/show-thread/'.$thread['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('user',$user);
	$this->view->assign('thread',$thread);
    }
    
    public function deleteThread(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $forumService = parent::getService('forum','forum');
        if(!$thread = $forumService->getThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such thread',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
       
        $form = $this->getForm('forum','thread');
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
                    $thread = $forumService->editThread($values);
                    $thread->delete();
                }
		
		TK_Helper::redirect('/forum/show-category/'.$thread['Category']['slug']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('user',$user);
	$this->view->assign('thread',$thread);
    }
    
    public function setThreadActive(){
        $this->view->setNoRender();
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $forumService = parent::getService('forum','forum');
        if(!$thread = $forumService->getThread($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such thread',404);
        }
        
       
        if($thread->get('active')){
            $thread->set('active',0);
        }
        else{
            $thread->set('active',1);
        }
            
        $thread->save();
        
	TK_Helper::redirect('/forum/show-category/'.$thread['Category']['slug']);
	     
    }
    
    public function editPost(){
        Service::loadModels('forum', 'forum');
	
        $forumService = parent::getService('forum','forum');
        if(!$post = $forumService->getPost($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such post',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('forum','post');
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
		$forumService->editPost($values);
		
		TK_Helper::redirect('/forum/show-thread/'.$post['Thread']['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('user',$user);
	$this->view->assign('form',$form);
	$this->view->assign('post',$post);
    }
    
    public function deletePost(){
        Service::loadModels('forum', 'forum');
	
        $forumService = parent::getService('forum','forum');
        if(!$post = $forumService->getPost($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such post',404);
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('forum','post');
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
		$post = $forumService->editPost($values);
		$post->delete();
		TK_Helper::redirect('/forum/show-thread/'.$post['Thread']['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('user',$user);
	$this->view->assign('form',$form);
	$this->view->assign('post',$post);
    }
    
    public function addFavouriteForum(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $forumService = parent::getService('forum','forum');
        if(!$category = $forumService->getCategory($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_ARRAY)){
            throw new TK_Exception('No such forum',404);
        }
        
        $forumService->addCategoryToFavourite($category,$user);
        
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
        
    }
    
    public function removeFavouriteForum(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $forumService = parent::getService('forum','forum');
        
        $forumService->removeCategoryFromFavourite($GLOBALS['urlParams'][1],$user['id']);
        
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function showFavouriteForums(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $forumService = parent::getService('forum','forum');
        
        $favouriteCategories = $forumService->getFavouriteCategories($user['id'],true,Doctrine_Core::HYDRATE_ARRAY);
        $lastThreads = array();
        foreach($favouriteCategories as $favourite):
            $lastThread = $forumService->getLastCategoryThread($favourite['category_id'],Doctrine_Core::HYDRATE_ARRAY);
            $lastPost = $forumService->getLastCategoryPost($favourite['category_id'],Doctrine_Core::HYDRATE_ARRAY);
            
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
