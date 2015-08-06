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
    
    public function error404(){
        $this->getLayout()->setLayout('main');
    }
    
    public function index(){
        $this->getLayout()->setLayout('main');
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        
        if($user)
            TK_Helper::redirect('/account/my-account');


        $form = $this->getForm('user','register');
        $this->view->assign('form',$form);
       
    }
    
    public function rules(){
        $this->getLayout()->setLayout('page');
        
    }
    public function faq(){
        $this->getLayout()->setLayout('page');
        
    }
    
    public function privacyPolicy(){
        $this->getLayout()->setLayout('page');
        
    }
    public function support(){
        $this->getLayout()->setLayout('page');
        
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
        $this->getLayout()->setLayout('page');
        Service::loadModels('team', 'team');
        $this->actionStack($this, 'layoutHelper');
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        if(isset($_POST['username'])){
            $users = $userService->searchForUsers($_POST['username'],Doctrine_Core::HYDRATE_ARRAY);
            $this->view->assign('users',$users);
        }
    }
    
    public function setLang(){
        $view = $this->view;
        $view->setNoRender();
        $langs = array('pl','gb');
        
        $lang = $GLOBALS['urlParams']['id'];
        if(in_array($lang,$langs)){
            setcookie('lang',$lang,time() + (86400 * 30),'/');
        }
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function menu(){
        
    }
    
    public function showCalendar(){
        Service::loadModels('team', 'team');
        $notificationService = parent::getService('user','notification');
        $rallyService = parent::getService('rally','rally');
        
        
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $hasRallyNow = $rallyService->hasRallyNow($user['Team']['id']);
        $hasFriendlyInvitation = $rallyService->hasFriendlyInvitation($user['id']);
        $notifications = $notificationService->getAllUserNotifications($user['id'],10,Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('hasFriendlyInvitation',$hasFriendlyInvitation);
        $this->view->assign('hasRallyNow',$hasRallyNow);
        $this->view->assign('notifications',$notifications);
        
    }
    
    
    
}
?>
