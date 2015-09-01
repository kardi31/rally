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
        
        $loginForm = $this->getForm('user','login');
        $this->view->assign('loginForm',$loginForm);
        $this->getLayout()->setLayout('main');
    }
    
    public function forgotPassword(){
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
        
        $loginForm = $this->getForm('user','login');
        $this->view->assign('loginForm',$loginForm);
        
        $form = $this->getForm('user','forgotPassword');
        $this->view->assign('form',$form);
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                                
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                
                if(!$user = $userService->getReminderUser($username,$email)){
                    $this->view->assign('message','User not exists');
                }
                else{
                    $token = TK_Text::createUniqueToken();
                    
                    $user->set('token',$token);
                    $user->save();
                    $mailService->sendMail($email,'Your FastRally password reminder',$mailService::prepareReminderMail($user,$token));

                    TK_Helper::redirect('/forgot-password?msg=reminder+send');
                }
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->getLayout()->setLayout('main');
    }
    
    public function newPassword(){
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
        
        $loginForm = $this->getForm('user','login');
        $this->view->assign('loginForm',$loginForm);
        if(!$user = $userService->getNewPasswordUser($_GET['info'],$_GET['id'])){
            $this->view->assign('message','User not exists');
        }
        else{
            Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
              
            $newPassword = TK_Text::createRandomString();

            $values['salt'] = TK_Text::createUniqueToken();
            $values['token'] = TK_Text::createUniqueToken();
            $values['password'] = TK_Text::encode($newPassword, $values['salt']);

            $user->fromArray($values);
            $user->save();
            $mailService->sendMail($user->get('email'),'Your FastRally password reminder',$mailService::prepareNewPasswordMail($user,$newPassword));

            TK_Helper::redirect('/new-password?msg=password+send');

            Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
        }
        
        $this->getLayout()->setLayout('main');
    }
    
    public function index(){
        $this->getLayout()->setLayout('main');
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        
        if($user)
            TK_Helper::redirect('/account/my-account');


        $loginForm = $this->getForm('user','login');
        $this->view->assign('loginForm',$loginForm);
        
        $form = $this->getForm('user','register');
        $this->view->assign('form',$form);
       
    }
    
    public function rules(){
        $this->getLayout()->setLayout('page');
        
    }
    public function faq(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
//        var_dump($user);exit;
        if(!$user){
            $this->getLayout()->setLayout('nolog');
        }
        else{
            $this->getLayout()->setLayout('page');
        }
        
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
            $friends = $friendsService->getUserFriends($user['id'],Doctrine_Core::HYDRATE_ARRAY);
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
            
            $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
            
            $users = $userService->searchForUsers($username,Doctrine_Core::HYDRATE_ARRAY);
            $this->view->assign('users',$users);
        }
    }
    
    public function setLang(){
        $view = $this->view;
        $view->setNoRender();
        $langs = array('pl','gb');
        
        $lang = $GLOBALS['urlParams'][1];
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
        $messageService = parent::getService('user','message');
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $hasRallyNow = $rallyService->hasRallyNow($user['Team']['id']);
        $hasFriendlyInvitation = $rallyService->hasFriendlyInvitation($user['id']);
        $notifications = $notificationService->getAllUserNotifications($user['id'],10,Doctrine_Core::HYDRATE_ARRAY);
        $notReadedNotifications = $notificationService->countNotReadedNotifications($user['id']);
        $notReadedMessages = $messageService->getUserMessagesNotReaded($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        $this->view->assign('hasFriendlyInvitation',$hasFriendlyInvitation);
        $this->view->assign('hasRallyNow',$hasRallyNow);
        $this->view->assign('notifications',$notifications);
        $this->view->assign('notReadedMessages',$notReadedMessages);
        $this->view->assign('notReadedNotifications',$notReadedNotifications);
        
    }
    
    
    
}
?>
