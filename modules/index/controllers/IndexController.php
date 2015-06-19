<?php

class Index_Index extends Controller{
 
    private static $instance = NULL;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new Index_Index();
       return self::$instance;
    }
    
    public function index(){
        $this->getLayout()->setLayout('main');
        
    }
    
    public function showFriends(){
        Service::loadModels('team', 'team');
        $this->actionStack($this, 'layoutHelper');
        
        $userService = parent::getService('user','user');
        $friendsService = parent::getService('user','friends');
        
        $user = $userService->getAuthenticatedUser();
        if($user){
            $friends = $friendsService->getUserFriends($user['id']);
            $this->view->assign('friends',$friends);
        }
        
        $this->view->assign('user',$user);
    }
    
    public function searchUsers(){
        Service::loadModels('team', 'team');
        $this->actionStack($this, 'layoutHelper');
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $users = $userService->searchForUsers($_POST['username'],Doctrine_Core::HYDRATE_ARRAY);
        $this->view->assign('users',$users);
    }
    
    public function menu(){
        
    }
    
    public function showCalendar(){
        
    }
    
    
    
}
?>
