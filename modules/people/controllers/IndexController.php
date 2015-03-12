<?php

class People_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function createRandomPeople(){
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $data = array();
        $data['driver1_id'] = $peopleService->createRandomDriver(5)->get('id');
        $data['driver2_id'] = $peopleService->createRandomDriver(5)->get('id');
        $data['pilot1_id'] = $peopleService->createRandomPilot(5)->get('id');
        $data['pilot2_id'] = $peopleService->createRandomPilot(5)->get('id');
        
        $teamService->createRandomTeam($data);
    }
    
    public function sellPlayer(){
        
        $peopleService = parent::getService('people','people');
        
        $id = $GLOBALS['urlParams']['id'];
        $person = $peopleService->getPerson($id,'id',Doctrine_Core::HYDRATE_ARRAY);
        
        $form = new Form();
        $form->createElement('text','asking_price',array('validators' => array('int')),'Cena');
        $form->createElement('text','selling_fee',array('validators' => array('int')),'Cena');
        $form->getElement('selling_fee')->addParam('disabled','disabled');
        $days = $form->createElement('select','days',array(),'Test');
        $days->addMultiOptions(array(1,2,3));
        $form->createElement('submit','submit');
        
        if($form->isSubmit()){
            if($form->isValid()){
                echo "zle";exit;
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
            else{
                var_dump('good');exit;
            }
        }
        
        $this->view->assign('form',$form);
    }
    
    
}
?>
