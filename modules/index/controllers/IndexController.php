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
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            $this->getLayout()->setLayout('main');
        else{
            $this->getLayout()->setLayout('page');
        }
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
                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                }
                else{
                    $token = TK_Text::createUniqueToken();
                    
                    $user->set('token',$token);
                    $user->save();
                    $mailService->sendMail($email,'Your FastRally password reminder',$mailService::prepareReminderMail($user,$token));

                    TK_Helper::redirect('/forgot-password?msg=reminder+send');
                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                }
            }
        }
        
        $this->getLayout()->setLayout('main');
    }
    
    public function newPassword(){
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
        
        $loginForm = $this->getForm('user','login');
        $this->view->assign('loginForm',$loginForm);
        if(!isset($_GET['msg'])){
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
                $mailService->sendMail($user->get('email'),'Your FastRally new password',$mailService::prepareNewPasswordMail($user,$newPassword));

                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                TK_Helper::redirect('/new-password?msg=password+send');

            }
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
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user){
            $this->getLayout()->setLayout('nolog');
        }
        else{
            $this->getLayout()->setLayout('page');
        }
        
    }
    public function faq(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
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
        
        $mailService = parent::getService('user','mail');
        $supportService = parent::getService('user','support');
        
        $form = $this->getForm('user','support');
        $this->view->assign('form',$form);
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                // user can add a post once every 30 seconds
                if(!$supportService->checkLastUserSupportEnquiry($user)){
                    TK_Helper::redirect('/support?msg=too+fast');
                    exit;
                }
                
                $contactEmail = View::getInstance()->getSetting('contactEmail');
                
		$supportService->addSupportEnquiry($values,$user['id']);
		$mailService->sendMail($contactEmail,'You have new support enquiry - '.$supportService->getSupportCategory($values['section']),$mailService::prepareSupportMail($user,$values['content']));

		TK_Helper::redirect('/support?msg=enquiry+send');
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
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
        
        
        $form = $this->getForm('user','searchUser');
        $this->view->assign('form',$form);
        
        
        if($form->isSubmit()){
            if($form->isValid()){
            
                $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

                $users = $userService->searchForUsers($username,Doctrine_Core::HYDRATE_ARRAY);
                $this->view->assign('users',$users);
            }
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
    
    public function manual(){
        $this->getLayout()->setLayout('page');
        
    }
    
    public function topWorldList(){
        $this->getLayout()->setLayout('page');
        
        Service::loadModels('user', 'user');
	
        $teamService = parent::getService('team','team');
        
        
        $topList = $teamService->getTopWorldList();
        
        $this->view->assign('topList',$topList);
        
    }
    
}
?>
