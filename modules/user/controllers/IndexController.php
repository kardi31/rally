<?php

/* 
 * @author = Tomasz Kardas <kardi31@o2.pl>
 * 
 */

class User_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName);
    }
    
    public function register(){
        $this->setDifView('index', 'index');
        $this->getLayout()->setLayout('main');
        $userService = parent::getService('user','user');
        
        $mailService = parent::getService('user','mail');
        $inviteService = parent::getService('user','invite');
        
        if(isset($_GET['ref'])&&is_numeric($_GET['ref'])){
            $ref = true;
            if(!$invite = $inviteService->getFullInvite((int)$_GET['ref'],Doctrine_Core::HYDRATE_ARRAY)){
                $ref = false;
            }
            else{
                if($invite['email']!=trim($_GET['email'])){
                    $ref = false;
                }
            }
        }
        $form = $this->getForm('user','register');
        if(isset($ref)){
            if($ref==true){
                $form->getElement('email')->setValue($invite['email']);
                $form->getElement('invite')->setValue($invite['id']);
            }
        }
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                if($userService->getUser($values['email'],'email')!==false){
		    TK_Helper::redirect('/user/register?msg=email+exists');
                }
                elseif($userService->getUser($values['username'],'username')!==false){
		    TK_Helper::redirect('/user/register?msg=username+exists');
                }
                else{
                    $values['salt'] = TK_Text::createUniqueToken();
                    $values['token'] = TK_Text::createUniqueToken();
                    $values['password'] = TK_Text::encode($values['password'], $values['salt']);
                    $values['role'] = "user";
                    $ref = true;
                    
                    if(!$invite = $inviteService->getFullInvite((int)$values['invite'],Doctrine_Core::HYDRATE_ARRAY)){
                        $ref = false;
                    }
                    else{
                        if($invite['email']!=trim($values['email'])){
                            $ref = false;
                        }
                    }
                    if($ref){
                        $values['referer'] = $invite['user_id'];
                        $values['referer_paid'] = 0;
                        
                        $inviteService->removeInvite($invite['id']);
                    }
                    
                    $userService->saveUserFromArray($values,false);
                    
                    $mailService->sendMail($values['email'],View::getInstance()->translate('Your FastRally registration'),$mailService::prepareRegistrationMail($values['token'],$values['username']));
                
		    TK_Helper::redirect('/user/register-complete');
		
		}
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
            else{
//                $form->getMessage();
                $form->populate($_POST);
            }
        }
        $this->view->assign('form',$form);
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Rejestracja - FastRally');
        }
        else{
            $this->view->setHeadTitle('Register - FastRally');
        }
    }
    
    public function activate(){
        $this->getLayout()->setLayout('main');
        $trainingService = parent::getService('people','training');
        $userService = parent::getService('user','user');
        $mailService = parent::getService('user','mail');
        parent::getService('rally','rally');
        if(!$user = $userService->getUser($GLOBALS['urlParams'][1],'token')){
            throw new TK_Exception('No user',404);
        }
        elseif($user->get('active')){
            throw new TK_Exception('User already activated',404);
        }
        else{
            
            $peopleService = parent::getService('people','people');
            $teamService = parent::getService('team','team');
            $carService = parent::getService('car','car');
            $leagueService = parent::getService('league','league');
            
            try{
            
            $data = array();
            $data['user_id'] = $user['id'];
	    
            $team = $teamService->createRandomTeam($data,$user);
	    
	    $league = $leagueService->appendTeamToLeague($team['id']);
	    $league_level = $league['League']['league_level'];
	    $team->set('league_name',$league['league_name']);
	    $team->save();
	    
            
	    $carModel = $carService->getRandomLeagueCar($league_level);
	    $car = $carService->createNewTeamCar($carModel,$team['id']);
            $driver = $peopleService->createRandomDriver($league_level,$team['id']);
            $pilot = $peopleService->createRandomPilot($league_level,$team['id']);
            
            $driver->set('team_id',$team['id']);
            $driver->save();
            $pilot->set('team_id',$team['id']);
            $pilot->save();
            $car->set('team_id',$team['id']);
            $car->save();
            
            $teamService->addTeamMoney($team['id'],50000,8,'Initial FastRally bonus');            
            
            $ip = TK_Helper::getRealIpAddr();
            $hostname = gethostbyaddr($ip);
            
            $user->set('register_ip',$ip);
            $user->set('register_hostname',$hostname);
            $user->set('active',1);
            $user->save();
            
            
            $cardService = parent::getService('card','card');
            $cardService->createRandomCards($user['id'],7);
            $mailService->sendMail($user['email'],View::getInstance('Your FastRally account is now active'),$mailService::prepareConfirmActivationMail($user->get('username')));
                
            $message = "User has been activated";
            }
            catch(Exception $e){
                error_log($e->getMessage());
            }
        }
        
	$this->view->assign('message',$message);
    }
    
    public function login(){
        $this->setDifView('index', 'index');
        $this->getLayout()->setLayout('main');
        $userService = parent::getService('user','user');
        $loginService = parent::getService('user','login');
        
        
        $form = $this->getForm('user','register');
        $loginForm = $this->getForm('user','login');

        $user = $userService->getAuthenticatedUser();
        if($user)
            TK_Helper::redirect('/account/my-account');
        
        if($loginForm->isSubmit()){
            if($loginForm->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                $user = $userService->getUser($values['login'], 'username');
                if ($user && !$user->get('active')):
                        $loginService->saveLoginFromArray($user['id'],false);
                        TK_Helper::redirect('/user/login?msg=not+active');
                else:
                    if($user && $userService->authenticate($user,$values['password'])):
                        
                        if(isset($_SESSION['wrong_pword'])){
                            $_SESSION['wrong_pword'] = 0;
                        }
                        
                        $loginService->saveLoginFromArray($user['id']);
                        
                        // if checked rememberMe 
                        // set cookie to remember
                        // which is used in user service
                        if(isset($values['rememberMe'])){
                            $cookie_name = 'siteAuth';
                            $cookie_time = time() + (3600 * 24 * 30); // 30Days
                            setcookie($cookie_name, $user['username'], $cookie_time, "/");
                        }
                        // if remember me is not checked
                        // then unset cookie ( user does not want to remember username anymore)
                        else{
                            if(isset($_COOKIE['siteAuth'])){
                                setcookie('siteAuth', null, -1, '/');
                                unset($_COOKIE['siteAuth']);
                            }
                        }
                        TK_Helper::redirect('/account/my-account');
                    elseif(!$user):
                        if(!isset($_SESSION['wrong_pword'])){
                            $_SESSION['wrong_pword'] = 0;
                        }
                        $_SESSION['wrong_pword']++;
                        
                        $loginService->saveLoginFromArray($user['id'],false);
                        
                        TK_Helper::redirect('/user/login?msg=no+user');
                    else:
                        $loginService->saveLoginFromArray($user['id'],false);
                        if(!isset($_SESSION['wrong_pword'])){
                            $_SESSION['wrong_pword'] = 0;
                        }
                        $_SESSION['wrong_pword']++;
                        
                        TK_Helper::redirect('/user/login?msg=wrong+password');
                    endif;
                endif;
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('loginForm',$loginForm);
        $this->view->assign('form',$form);
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zaloguj się - FastRally');
        }
        else{
            $this->view->setHeadTitle('Log in - FastRally');
        }
    }
    
    public function quickLogin(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        
        if(isset($_POST)){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
//                var_dump($values);exit;
//                var_dump($values);exit;
                $user = $userService->getUser($values['login'], 'username');
                if ($user && !$user->get('active')):
                    $this->view->messages()->add($this->view->translate('User is not active'), 'error');
                    echo "blad";exit;
                else:
                    if($userService->quickAuthenticate($user)):
                        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
                    else:
                        echo "zleeee";exit;
                    endif;
                endif;
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                echo "zle";exit;
                exit;
        }
        
        $this->view->assign('form',$form);
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Aktywuj konto - FastRally');
        }
        else{
            $this->view->setHeadTitle('Activate account - FastRally');
        }
    }
    
    public function logout(){
        $userService = parent::getService('user','user');
        
        // unset cookie to remember
        if(isset($_COOKIE['siteAuth'])){
            setcookie('siteAuth', null, -1, '/');
            unset($_COOKIE['siteAuth']);
        }
        if($userService->logout()){
            TK_Helper::redirect('/');
        }
        exit;
                
    }
    
    public function registerComplete(){
        
        $loginForm = $this->getForm('user','login');
        $this->view->assign('loginForm',$loginForm);
        
	$this->getLayout()->setLayout('main');
    }
    
    public function findUser(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        
        $query = filter_var($_GET['q'],FILTER_SANITIZE_STRING);
            
        $responseUsers = array();
        $counter = 0;
        
        if(strlen($query)>=3){
            $users = $userService->findUsers($query,Doctrine_Core::HYDRATE_ARRAY);

            if(isset($users)){
                foreach($users as $user){
                    $responseUsers[$counter]['label'] = $user['username'];
                    $responseUsers[$counter]['value'] = $user['username'];
                    $counter++;
                }
            }
        }
        echo json_encode($responseUsers);
    }
    
    public function inviteFriend(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $friendsService = parent::getService('user','friends');
        
        $id = filter_var($_POST['id'],FILTER_VALIDATE_INT);
        
        
        $invite = $friendsService->inviteUser($id,$user['id']);
        
        
        $notificationService->addNotification('You have been invited to friend list by '.$user['username'],2,$id,$invite['id']);
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
        
    }
    
    
    public function readNotifications(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        try{
            $user = $userService->getAuthenticatedUser();
            
            $notificationService->readNotifications($user['id']);
            
            echo "ok";
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
        
    }
    
    public function deleteNotification(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        $id = filter_var($GLOBALS['urlParams'][1],FILTER_VALIDATE_INT);
        try{
            $user = $userService->getAuthenticatedUser();
            
            $notification = $notificationService->getNotification($id);
            if($user['id']==$notification['user_id']){
                $notification->delete();
            }
            else{
                echo "wrong user";
            }
            echo "ok";
        } catch (Exception $ex) {
            var_dump($ex->getMessage());
        }
        
    }
    
    public function acceptInvite(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $friendsService = parent::getService('user','friends');
        
        $id = filter_var($GLOBALS['urlParams'][1],FILTER_VALIDATE_INT);
        
        $friendsService->acceptInviteUser($id,$user['id']);
        
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function rejectInvite(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $friendsService = parent::getService('user','friends');
        
        $id = filter_var($GLOBALS['urlParams'][1],FILTER_VALIDATE_INT);
        
        $friendsService->rejectInviteUser($id,$user['id']);
        
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function removeFriend(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $notificationService = parent::getService('user','notification');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $friendsService = parent::getService('user','friends');
        
        $id = filter_var($GLOBALS['urlParams'][1],FILTER_VALIDATE_INT);
        
        $inviteUser = $friendsService->getInviteUser($id);
        
        if(!($inviteUser['UserFriends']['id'] == $user['id']||$inviteUser['User']['id'] == $user['id'])){
            TK_Helper::redirect('/');
            exit;
        }
        
        $inviteUser->delete();
        
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function inviteToGame(){
        Service::loadModels('team', 'team');
        $this->getLayout()->setLayout('page');
        
        $userService = parent::getService('user','user');
        $inviteService = parent::getService('user','invite');
        $mailService = parent::getService('user','mail');
        
        $user = $userService->getAuthenticatedUser();
        
        $userInvites = $inviteService->getUserInvites($user['id']);
        $userAcceptedInvites = $userService->getUsersWithReferer($user['id']);
        
	$form = $this->getForm('user','InviteToGame');
        $this->view->assign('form',$form);
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $email = $_POST['email'];
                
                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    TK_Helper::redirect('/user/invite-to-game?msg=not+email');
                }
                elseif($inviteService->checkEmailInvited($email, $user['id'])){
                    TK_Helper::redirect('/user/invite-to-game?msg=user+already+invited');
                }
                elseif($userService->getUser($email,'email')){
                    TK_Helper::redirect('/user/invite-to-game?msg=user+already+ingame');
                }
                else{
                    $invite = $inviteService->saveInviteFromArray($email,$user['id']);
                    $mailService->sendMail($email,'You have been invited to FastRally',$mailService::prepareInvitationMail($email,$user,$invite));

                    TK_Helper::redirect('/user/invite-to-game?msg=user+invited');
                }
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        $this->view->assign('userAcceptedInvites',$userAcceptedInvites);
        $this->view->assign('userInvites',$userInvites);
        
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zaproś znajomego - FastRally');
        }
        else{
            $this->view->setHeadTitle('Invite friend - FastRally');
        }
    }
    
    public function mailTemplate(){
        $this->view->disableLayout();
    }
    
//    public function addMessage(){
//        Service::loadModels('team', 'team');
//        $this->getLayout()->setLayout('page');
//	
//        $userService = parent::getService('user','user');
//        $messageService = parent::getService('user','message');
//        if(!$user = $userService->getUser($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
//            throw new TK_Exception('No such thread',404);
//        }
//        
//        $authenticatedUser = $userService->getAuthenticatedUser();
//        
//        $form = $this->getForm('user','message');
//       
//        if($form->isSubmit()){
//            if($form->isValid()){
//                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
//                
////                // user can add a post once every 30 seconds
////                if(!$forumService->checkLastUserPost($user,$thread)){
////                    TK_Helper::redirect('/forum/show-thread/id/'.$thread['id'].'?msg=too+fast');
////                    exit;
////                }
////                
//                $values = $_POST;
////		
//		$messageService->addMessage($user['id'],$authenticatedUser['id'],$values);
//		TK_Helper::redirect('/team/show-team/id/'.$user['Team']['id']."?msg=message+sent");
////		
//                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
//            }
//        }
//        
//	$this->view->assign('user',$user);
//	$this->view->assign('form',$form);
//    }
    
    public function showMessageBox(){
        Service::loadModels('team', 'team');
        $this->getLayout()->setLayout('page');
	
        $userService = parent::getService('user','user');
        $messageService = parent::getService('user','message');
        if(!$user = $userService->getUser($GLOBALS['urlParams'][1],'id',Doctrine_Core::HYDRATE_RECORD)){
            throw new TK_Exception('No such user',404);
        }
        
        $authenticatedUser = $userService->getAuthenticatedUser();
        
        $messages = $messageService->getUserMessages($user['id'],Doctrine_Core::HYDRATE_ARRAY);
        
        if($authenticatedUser['id']==$user['id']){
            $messageService->setMessagesReaded($user['id']);
        }
        
        $form = $this->getForm('user','message');
       
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
//                // user can add a post once every 30 seconds
                if(!$messageService->checkLastUserMessage($authenticatedUser['id'],$user['id'])){
                    TK_Helper::redirect('/user/show-message-box/'.$user['id'].'?msg=too+fast');
                    exit;
                }
                
                
                $values = $_POST;
		$messageService->addMessage($user['id'],$authenticatedUser['id'],$values);

                TK_Helper::redirect('/user/show-message-box/'.$user['id']."?msg=message+sent");
//		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
	$this->view->assign('user',$user);
	$this->view->assign('messages',$messages);
	$this->view->assign('form',$form);
        
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Lista wiadomości - FastRally');
        }
        else{
            $this->view->setHeadTitle('Message box - FastRally');
        }
    }
    
    public function myAccount(){
        $this->getLayout()->setLayout('page');
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Moje konto - FastRally');
        }
        else{
            $this->view->setHeadTitle('My account - FastRally');
        }
    }
    
    public function changePassword(){
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
        
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $form = $this->getForm('user','changePassword');
        $this->view->assign('form',$form);
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $oldPw = $_POST['oldpw'];
                
                $checkNewPwString = TK_Text::encode($oldPw, $user['salt']);
                $checkNewPw = ($checkNewPwString==$user['password']);
                
                if(!$checkNewPw){
                    TK_Helper::redirect('/user/change-password?msg=old+wrong');
                }
                elseif($_POST['password']!=$_POST['password2']){
                    TK_Helper::redirect('/user/change-password?msg=not+match');
                }
                else{
                    $newPassword = $_POST['password'];

                    $salt = TK_Text::createUniqueToken();
                    $token = TK_Text::createUniqueToken();
                    $newPasswordEncoded = TK_Text::encode($newPassword, $salt);
                    
                    $user->set('salt',$salt);
                    $user->set('token',$token);
                    $user->set('password',$newPasswordEncoded);
                    $user->save();
                    
                    $userService->authenticate($user,$newPassword);

                    TK_Helper::redirect('/user/change-password?msg=changed');
                    Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                   
                }
            }
        }
        
        $this->getLayout()->setLayout('page');
        
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zmień hasło - FastRally');
        }
        else{
            $this->view->setHeadTitle('Change password - FastRally');
        }
    }
    
    public function changeEmail(){
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
        
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $form = $this->getForm('user','changeEmail');
        $this->view->assign('form',$form);
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $oldPw = $_POST['password'];
                $checkNewPwString = TK_Text::encode($oldPw, $user['salt']);
                $checkNewPw = ($checkNewPwString==$user['password']);
                
                if(!$checkNewPw){
                    TK_Helper::redirect('/user/change-email?msg=password+wrong');
                }
                
                if(!$user2 = $userService->getUser($_POST['oldemail'],'email')){
                    TK_Helper::redirect('/user/change-email?msg=old+email+wrong');
                    exit;
                }
                
                if($user2['id'] != $user['id']){
                    TK_Helper::redirect('/user/change-email?msg=old+email+wrong');
                    exit;
                }
                if($userService->getUser($_POST['newemail'],'email')){
                    TK_Helper::redirect('/user/change-email?msg=email+registered');
                    exit;
                }      
                
                $newEmail = $_POST['newemail'];

                

                $user->set('email',$newEmail);
                $user->save();

                $userService->refreshAuthentication();

                TK_Helper::redirect('/user/change-email?msg=changed');
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
                   
            }
        }
        
        $this->getLayout()->setLayout('page');
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zmień email - FastRally');
        }
        else{
            $this->view->setHeadTitle('Change email - FastRally');
        }
        
    }
    
    public function editDetails(){
        Service::loadModels('team', 'team');
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
        
        
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $form = $this->getForm('user','editDetails');
        
        $populateArray = $user->toArray();
        $populateArray['anonymous'] = $populateArray['hide_details'];
//        $form->getElement('anonymous')->setValue($user['hide_details']);
        $form->populate($populateArray);
        $this->view->assign('form',$form);
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $values['hide_details'] = $values['anonymous'];
                
                $user->fromArray($values);
                $user->save();
                
                $userService->refreshAuthentication();
                TK_Helper::redirect('/user/edit-details?msg=changed');
            }
        }
        
        $this->getLayout()->setLayout('page');
        
        
        
        if(isset($GLOBALS['lang'])&&$GLOBALS['lang']=='pl'){
            $this->view->setHeadTitle('Zmień dane - FastRally');
        }
        else{
            $this->view->setHeadTitle('Change details - FastRally');
        }
    }
}
?>
