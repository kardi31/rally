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
                    $form->setError('Ten adres email jest już zarejestrowany');
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
                    
                    $mailService->sendMail($values['email'],'Rejestracja w Tomek CMS przebiegła pomyślnie',$mailService::prepareRegistrationMail($values['token']));
                
		    TK_Helper::redirect('/user/register-complete');
		
		}
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('form',$form);
    }
    
    public function activate(){
        
               
        $trainingService = parent::getService('people','training');
        $userService = parent::getService('user','user');
        $mailService = parent::getService('user','mail');
        
        if(!$user = $userService->getUser($GLOBALS['urlParams']['token'],'token')){
            $message = "brak użytkownika";
        }
        elseif($user->get('active')){
            $message = "Użytkownik już aktywowany";
        }
        else{
            
            $peopleService = parent::getService('people','people');
            $teamService = parent::getService('team','team');
            $carService = parent::getService('car','car');
            $leagueService = parent::getService('league','league');
            
	    
            $data = array();
            $data['user_id'] = $user['id'];
	    
            $team = $teamService->createRandomTeam($data,$user['id']);
	    
	    $league = $leagueService->appendTeamToLeague($team['id']);
	    
	    $league_level = $league['League']['league_level'];
	    
	    $carModel = $carService->getRandomLeagueCar($league_level);
	    $team['Car1'] = $carService->createNewTeamCar($carModel);
            $team['Driver1'] = $peopleService->createRandomDriver($league_level);
            $team['Pilot1'] = $peopleService->createRandomPilot($league_level);
	    $team->set('league_name',$league['league_name']);
	    $team->save();
	    
            $team->get('Pilot1')->set('team_id',$team['id']);
            $team->get('Driver1')->set('team_id',$team['id']);
            $team->save();
            
            $user->set('active',1);
            $user->save();
            $mailService->sendMail($user['email'],'Konto w Tomek CMS zostało aktywowane',$mailService::prepareConfirmActivationMail());
                
            $message = "Użytkownik pomyślnie aktywowany";
        }
        
	$this->view->assign('message',$message);
    }
    
    public function login(){
        $userService = parent::getService('user','user');
        
        $user = $userService->getAuthenticatedUser();
        if($user)
            TK_Helper::redirect('/user/my-account');
        
        $form = new Form();
//        $form->createElement('text','email',array('validators' => array('stringLength' => array('min' => 4,'max' => 30)),'Email'));
//        $form->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $form->createElement('submit','submit');
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
//                var_dump($values);exit;
                $user = $userService->getUser($values['login'], 'username');
                if ($user && !$user->get('active')):
                    $this->view->messages()->add($this->view->translate('User is not active'), 'error');
                else:
                    if($userService->authenticate($user,$values['password'])):
                        TK_Helper::redirect('/account/my-account');
                    endif;
                endif;
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
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
        
        if($userService->logout()){
            TK_Helper::redirect('/');
        }
        exit;
                
    }
    
    public function registerComplete(){
	
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
//                    echo "Hello, your friend ".$user['username']." has invited you to Fast Rally. To join, please click <a href='/user/register/ref/".$invite['id']."'> join fast rally</a>";
//                    exit;
//                    $user = $userService->getUser($values['email'], 'email');
//                    if ($user && !$user->get('active')):
//                        $this->view->messages()->add($this->view->translate('User is not active'), 'error');
//                    else:
//                        if($userService->authenticate($user,$values['password'])):
//                            TK_Helper::redirect('/account/invite-to-game');
//                        endif;
//                    endif;
                }
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        $this->view->assign('userAcceptedInvites',$userAcceptedInvites);
        $this->view->assign('userInvites',$userInvites);
//            $to = 'kardi31@tlen.pl';
//
//    $subject = 'Website Change Request';
//
//    $headers = "From: tomekvarts@o2.pl \r\n";
//    $headers .= "Reply-To: tomekvarts@o2.pl \r\n";
//    $headers .= "MIME-Version: 1.0\r\n";
//    $headers .= "Content-Type: text/html; charset=ISO-8859-2\r\n";
//
//    $message = '<html><body>';
//    $message .= '<h1>Hello, World!</h1>';
//    $message .= '</body></html>';
//    mail($to, $subject, $message, $headers);
//echo "ok";exit;
    }
}
?>
