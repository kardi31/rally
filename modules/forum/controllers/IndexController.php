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
	
        $forumService = parent::getService('forum','forum');
        
        $categories = $forumService->getAllCategoriesInformation(Doctrine_Core::HYDRATE_ARRAY);
	$this->view->assign('categories',$categories);
    }
    
    
    
}
?>
