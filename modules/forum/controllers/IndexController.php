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
        
        
        
        $threads = $forumService->getAllCategoryThreads($category['id'],Doctrine_Core::HYDRATE_ARRAY);
       
	$this->view->assign('category',$category);
	$this->view->assign('threads',$threads);
    }
    
    
    
}
?>
