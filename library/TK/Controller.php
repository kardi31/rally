<?php
 
class Controller{
         
        protected $view;
	protected $layout;
 
    public function __construct(){
        $this->view = new View();
        $this->layout = new Layout();
        
    }
    
    public function layoutHelper(){
	
	if ( !class_exists('UserService')){
	    $userService = $this->getService('user', 'user');
	}
	else{
	    $userService = new UserService();
	}
	
        $authenticatedUser = $userService->getAuthenticatedUser();
	$this->view->assign('authenticatedUser',$authenticatedUser);
    }
    
    public function _render($elem,$viewName,$zone='index') {
        $parts = explode('-',$viewName);
        $actionName = "";
        foreach($parts as $part):
            $part = ucfirst($part);
            $actionName .= $part;
        endforeach;
        $actionName = lcfirst($actionName);
        $elem->$actionName();
        $module = explode('_',get_class($elem));
	
        if($zone=="admin"){
            $this->layout->setLayout('admin');
        }
	$this->layoutHelper();
	// show view file on its place in layout
	$this->layout->view = $elem->view;
        if($elem->view->render != 0){
            $this->layout->content = $elem->view->render($module[0],$viewName,$zone);
            $this->layout->render();
        }
	
    }
    
    public function getService($module,$service) {
        $className = ucfirst($service)."Service";
        if(class_exists($className)){
            return $className::getInstance();
        }
        require BASE_PATH."/modules/".$module."/services/".ucfirst($service).".php";
        
        return $className::getInstance();
    }
    
    public function actionStack($controller,$function){
	$controller->$function();
    }
}