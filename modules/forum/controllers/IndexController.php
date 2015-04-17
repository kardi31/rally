<?php

class Forum_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
    }
    
    public function showForum(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $forumService = parent::getService('forum','forum');
        
        $categories = $forumService->getAllCategories(Doctrine_Core::HYDRATE_ARRAY);
        foreach($categories as $key=>$category):
            $categories[$key]['last_post'] = $forumService->getLastCategoryPost($category['id'],Doctrine_Core::HYDRATE_ARRAY);
            $categories[$key]['thread_count'] = $forumService->countCategoryThreads($category['id']);
            $categories[$key]['post_count'] = $forumService->countCategoryPosts($category['id']);
        endforeach;
	$this->view->assign('categories',$categories);
    }
    
    public function showCategory(){
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
	
        $forumService = parent::getService('forum','forum');
        if(!$category = $forumService->getCategory($GLOBALS['urlParams']['slug'],'slug',Doctrine_Core::HYDRATE_ARRAY)){
            echo "error";exit;
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $threads = $forumService->getAllCategoryThreads($category['id'],Doctrine_Core::HYDRATE_ARRAY);
       
        $form = $this->getForm('forum','thread');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
		$forumService->addThread($values,$category['id'],$user);
		
		TK_Helper::redirect('/forum/show-category/slug/'.$category['slug']);
		
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
        if(!$thread = $forumService->getFullThread($GLOBALS['urlParams']['id'],'id',Doctrine_Core::HYDRATE_ARRAY)){
            echo "error";exit;
        }
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
        $form = $this->getForm('forum','post');
        
        $posts = $forumService->getAllThreadPosts($thread['id'],Doctrine_Core::HYDRATE_ARRAY);
       
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
		$forumService->addPost($values,$thread['id'],$user);
		
		TK_Helper::redirect('/forum/show-thread/id/'.$thread['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
	$this->view->assign('form',$form);
	$this->view->assign('posts',$posts);
	$this->view->assign('thread',$thread);
    }
    
    
}
?>
