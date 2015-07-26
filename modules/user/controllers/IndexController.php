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
        
        if(isset($_GET['ref'])&&is_numeric($_GET['ref'])){
            $ref = true;
            $inviteService = parent::getService('user','invite');
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
        if($ref){
            $form->getElement('email')->setValue($invite['email']);
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
                    if($ref){
                        $values['referer'] = $invite['user_id'];
                        $values['referer_paid'] = 0;
                        
                        $inviteService->removeInvite($invite['id']);
                    }
                    
                    $userService->saveUserFromArray($values,false);
                    
                    $mailService->sendMail($values['email'],'Your FastRally registration',$mailService::prepareRegistrationMail($values['token']));
                
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
    }
    
    public function activate(){
        $this->getLayout()->setLayout('main');
        $trainingService = parent::getService('people','training');
        $userService = parent::getService('user','user');
        $mailService = parent::getService('user','mail');
        parent::getService('rally','rally');
        if(!$user = $userService->getUser($GLOBALS['urlParams']['token'],'token')){
            $message = "No user";
        }
        elseif($user->get('active')){
            $message = "User already activated";
        }
        else{
            
            $peopleService = parent::getService('people','people');
            $teamService = parent::getService('team','team');
            $carService = parent::getService('car','car');
            $leagueService = parent::getService('league','league');
            
            try{
            
            $data = array();
            $data['user_id'] = $user['id'];
	    
            $team = $teamService->createRandomTeam($data,$user['id']);
	    
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
            
            
            $user->set('active',1);
            $user->save();
            $mailService->sendMail($user['email'],'Your FastRally account is now active',$mailService::prepareConfirmActivationMail());
                
            $message = "User has been activated";
            }
            catch(Exception $e){
                var_dump($e->getMessage());exit;
            }
        }
        
	$this->view->assign('message',$message);
    }
    
    public function login(){
        $this->setDifView('index', 'index');
        $this->getLayout()->setLayout('main');
        $userService = parent::getService('user','user');
        
        
        $form = $this->getForm('user','register');
        $loginForm = $this->getForm('user','login');

        if($loginForm->isSubmit()){
            if($loginForm->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                $user = $userService->getUser($values['login'], 'username');
                if ($user && !$user->get('active')):
                    $this->view->messages()->add($this->view->translate('User is not active'), 'error');
                else:

                    if($user && $userService->authenticate($user,$values['password'])):
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
                        TK_Helper::redirect('/user/login?msg=no+user');
                    else:
                        TK_Helper::redirect('/user/login?msg=wrong+password');
                    endif;
                endif;
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('loginForm',$loginForm);
        $this->view->assign('form',$form);
        
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
	$this->getLayout()->setLayout('main');
    }
    
    public function findUser(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        
        $query = $_GET['q'];
        
        $users = $userService->findUsers($query,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        $responseUsers = array();
        $counter = 0;
        if(isset($users)){
            foreach($users as $user){
                $responseUsers[$counter]['label'] = $user;
                $responseUsers[$counter]['value'] = $user;
                $counter++;
            }
        }
        echo json_encode($responseUsers);
    }
    
    public function inviteFriend(){
        $view = $this->view;
        $view->setNoRender();
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $friendsService = parent::getService('user','friends');
        
        $id = filter_var($_POST['id'],FILTER_VALIDATE_INT);
        
        $friendsService->inviteUser($id,$user['id']);
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    
    public function inviteToGame(){
        Service::loadModels('team', 'team');
        $this->getLayout()->setLayout('page');
        
        $userService = parent::getService('user','user');
        $inviteService = parent::getService('user','invite');
        
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

                    TK_Helper::redirect('/user/invite-to-game?msg=user+invited');
                }
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        $this->view->assign('userAcceptedInvites',$userAcceptedInvites);
        $this->view->assign('userInvites',$userInvites);
    }
    
    public function mailTemplate(){
        $this->view->disableLayout();
    }
}
?>
