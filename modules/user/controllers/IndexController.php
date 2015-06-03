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
        
        $userService = parent::getService('user','user');
        $mailService = parent::getService('user','mail');
        
        $form = new Form();
        $form->createElement('text','login',array('validators' => array('stringLength' => array('min' => 4,'max' => 12)),'Login'));
        $form->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $form->createElement('password','password2',array('validators' => array('match' => array('elem' => 'password'))),'Powtórz hasło');
        $form->createElement('text','email',array('validators' => 'email'),'Adres email');
        $form->createElement('captcha','captcha',array());
        $form->createElement('submit','submit');
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
        $form->createElement('text','email',array('validators' => array('stringLength' => array('min' => 4,'max' => 30)),'Email'));
        $form->createElement('password','password',array('validators' => array('stringLength' => array('min' => 4,'max' => 12))),'Hasło');
        $form->createElement('submit','submit');
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $user = $userService->getUser($values['email'], 'email');
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
    
    public function logout(){
        $userService = parent::getService('user','user');
        
        if($userService->logout())
            TK_Helper::redirect('/');
                
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
        foreach($users as $user){
            $responseUsers[$counter]['label'] = $user;
            $responseUsers[$counter]['value'] = $user;
            $counter++;
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
    
}
?>
