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
        Service::loadModels('market', 'market');
        Service::loadModels('rally', 'crew');
        
        $marketService = parent::getService('market','market');
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        
        $id = $GLOBALS['urlParams']['id'];
        $player = $peopleService->getPerson($id,'id',Doctrine_Core::HYDRATE_RECORD);
        
        $form = new Form();
        $form->createElement('text','asking_price',array('validators' => array('int')),'Cena');
        $form->createElement('text','selling_fee',array('validators' => array('int')),'Cena');
        $form->getElement('selling_fee')->addParam('readonly','readonly');
        $form->getElement('asking_price')->addParam('autocomplete','off');
        $days = $form->createElement('select','days',array(),'Test');
        $days->addMultiOptions(array(1,2,3));
        $form->createElement('submit','submit');
        
        if($form->isSubmit()){
            if($form->isValid()){
                Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
                
                $result = $marketService->addPlayerOnMarket($values,$player);
                
                if($result!== false)
                    TK_Helper::redirect('/account/my-drivers');
                else{
                    $this->view->assign('message','Player already on market');
                }
                
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
            }
        }
        
        $this->view->assign('form',$form);
        $this->view->assign('player',$player);
    }
    
    
}
?>
