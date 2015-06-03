<?php
 
class Controller{
         
        protected $view;
	protected $layout;
	public $responseSegment;
 
    public function __construct(){
        $this->view = View::getInstance();
        $this->layout = Layout::getInstance();
        $this->responseSegment = array();
        
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
        $actionName = TK_Text::convertViewToActionName($viewName);
        $elem->$actionName();
        $module = explode('_',get_class($elem));
	
        $this->setUserLastActive();
        
        if($zone=="admin"){
            $this->layout->setLayout('admin');
        }
	$this->layoutHelper();
	// show view file on its place in layout
	$this->layout->view = $elem->view;
        $this->layout->controller = $this;
        
        $this->layout->checkResponseSegments();
        
        
        if($elem->view->render != 0){
            $this->layout->content = $elem->view->render($module[0],$viewName,$zone);
            
            
            // setting up response segments
            foreach($this->responseSegment as $responseSegment => $details){
                $actionName = TK_Text::convertViewToActionName($details['view']);
                $controllerName = ucfirst($details['module'])."_".ucfirst($details['controller']);
//                var_dump($controllerName);exit;
                if(!class_exists($controllerName)){
                    require_once(BASE_PATH."/modules/".$details['module']."/controllers/".ucfirst($details['controller'])."Controller.php");
                }
                
                $controllerName::getInstance()->$actionName();
                
                $this->layout->responseSegment[$responseSegment] = $elem->view->render($details['module'],$details['view'],$zone);
            }
            
            
            
            $this->layout->render();
        }
        
	
    }
    
    public function setResponseSegment($segment,$module,$controller,$view){
        $this->responseSegment[$segment] = array('module' => $module,'controller' => $controller,'view' => $view);
    }
    
    public function getService($module,$service) {
        $className = ucfirst($service)."Service";
        if(class_exists($className)){
            return $className::getInstance();
        }
        require BASE_PATH."/modules/".$module."/services/".ucfirst($service).".php";
        
        return $className::getInstance();
    }
    
    public function getForm($module,$form) {
        $className = ucfirst($form);
        require BASE_PATH."/modules/".$module."/form/".ucfirst($form).".php";
        
        return new $className();
    }
    
    public function actionStack($controller,$function){
	$controller->$function();
    }
    
    
    public function setUserLastActive(){
        $userService = $this->getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if($user){
            $user->set('last_active',date('Y-m-d H:i:s'));
            $user->save();
        }
    }
}