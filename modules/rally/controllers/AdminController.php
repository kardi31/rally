<?php


/* 
 * @author = Tomasz Kardas <kardi31@o2.pl>
 * 
 */
class Rally_Admin extends Controller{
    
     public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
     public function login(){
     }
     
     public function listRally(){
     }
     
     public function listRallyData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('rally', 'rally');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Rally_Model_Doctrine_Rally', 
            'class' => 'Rally_DataTables_Rally', 
            'fields' => array('r.id','r.name','r.date','r.active','r.big_awards','r.league_rally','r.friendly','r.finished'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['name'];
             $row[] = TK_Text::timeFormat($result['date'],'d/m/Y H:i');
             if($result['active'])
                 $row[] = '<span class="label label-sm label-success">Aktywny</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Nieaktywny</span>';
	     
             if($result['big_awards'])
                 $row[] = '<span class="label label-sm label-success">Tak</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Nie</span>';
             
             if($result['league_rally'])
                 $row[] = '<span class="label label-sm label-success">Tak</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Nie</span>';
             
             if($result['friendly'])
                 $row[] = '<span class="label label-sm label-success">Tak</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Nie</span>';
             
             if($result['finished'])
                 $row[] = '<span class="label label-sm label-success">Tak</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Nie</span>';
             
             $options = '<a href="/admin/rally/show-rally-crews/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
             $options .='<a href="/admin/rally/show-rally-stages/id/'.$result['id'].'" class="btn default btn-xs blue"><i class="fa fa-list"></i> Stages </a>';
             if($result['finished']){
                $options .='<a href="/admin/rally/show-rally-result/id/'.$result['id'].'" class="btn default btn-xs green"><i class="fa fa-list"></i> Results </a>';
             }
             $options .='<a href="/admin/rally/edit-rally/id/'.$result['id'].'" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Edit </a>';
	     
	     $row[] = $options;
	     
	     $rows[] = $row;
         endforeach;
  
         $response = array(
            "aaData" => $rows,
            "sEcho" => (int)$_REQUEST['sEcho'],
            "iTotalRecords" => $results['totalRecords'],
            "iTotalDisplayRecords" => $results['totalRecords']
        );
         
        echo json_encode($response,JSON_UNESCAPED_SLASHES);
     }
     
     public function showRallyCrews(){
     }
     
     public function showRallyCrewsData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('rally', 'rally');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         Service::loadModels('car', 'car');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Rally_Model_Doctrine_Crew', 
            'class' => 'Rally_DataTables_Crew', 
            'fields' => array('rc.id','t.name','d.last_name','p.last_name','cm.name','rc.created_at','rc.in_race','rc.risk'),
        ));
	 
         $iTotalRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['Team']['name'];
             $row[] = $result['Driver']['last_name']." ".$result['Driver']['first_name'];
             $row[] = $result['Pilot']['last_name']." ".$result['Pilot']['first_name'];
             $row[] = $result['Car']['Model']['name'];
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             $options = '<a href="/admin/rally/show-rally-crew-details/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            
	     $row[] = $options;
	     
	     $rows[] = $row;
         endforeach;
         
	  $response = array(
            "aaData" => $rows,
            "sEcho" => (int)$_REQUEST['sEcho'],
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $results['totalRecords']
        );
       
        echo json_encode($response);
     }
     
     public function showStageResult(){
	 
	$stage_id = $GLOBALS['urlParams']['id'];
        $rallyService = parent::getService('rally','rally');
	$stage = $rallyService->getStageWithRally($stage_id,'id',Doctrine_Core::HYDRATE_ARRAY);
        
	$this->view->assign('stage',$stage);
     }
     
     public function showStageResultData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('rally', 'rally');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         Service::loadModels('car', 'car');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Rally_Model_Doctrine_StageResult', 
            'class' => 'Rally_DataTables_StageResult', 
            'fields' => array('sr.id','t.name','sr.base_time','a.name','cr.risk','c.name','d.last_name','p.last_name','c.name','sr.out_of_race'),
        ));
	 
         $iTotalRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['Crew']['Team']['name'];
             $row[] = $result['base_time'];
	     if(isset($result['Accident'])){
             $row[] = $result['Accident']['name'];
		 
	     }
	 else {
	     $row[] = '';
	 }
             $row[] = $result['Crew']['risk'];
             $row[] = $result['Crew']['Driver']['last_name']." ".$result['Crew']['Driver']['first_name'];
             $row[] = $result['Crew']['Pilot']['last_name']." ".$result['Crew']['Pilot']['first_name'];
             $row[] = $result['Crew']['Car']['name'];
             if(!$result['out_of_race'])
                 $row[] = '<span class="label label-sm label-success">W wyścigu</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Poza trasą</span>';
	     
             $options = '<a href="/admin/rally/show-rally-crew-details/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            
	     $row[] = $options;
	     
	     $rows[] = $row;
         endforeach;
         
	  $iDisplayLength = intval($_REQUEST['iDisplayLength']);
	  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
	  $sEcho = intval($_REQUEST['sEcho']);
         
         $response = array(
            "aaData" => $rows,
            "sEcho" => $sEcho,
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
        );
       
        echo json_encode($response);
     }
     
     public function showRallyResult(){
	 
	$rally_id = $GLOBALS['urlParams']['id'];
        $rallyService = parent::getService('rally','rally');
	$rally = $rallyService->getRally($rally_id,'id',Doctrine_Core::HYDRATE_RECORD);
        
	$this->view->assign('rally',$rally);
     }
     
     public function showRallyResultData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('rally', 'rally');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         Service::loadModels('car', 'car');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Rally_Model_Doctrine_RallyResult', 
            'class' => 'Rally_DataTables_RallyResult', 
            'fields' => array('r.id','t.name','r.total_time','r.out_of_race','r.stage_out_number'),
        ));
	 
         $iTotalRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['Crew']['Team']['name'];
             $row[] = $result['total_time'];
             if(!$result['out_of_race']){
                 $row[] = '<span class="label label-sm label-success">W wyścigu</span>';
                 $row[] = $result['stage_out_number'];
             }
             else{
                 $row[] = '<span class="label label-sm label-danger">Poza trasą</span>';
                 $row[] = $result['stage_out_number'];
             }
             
             $options = '<a href="/admin/rally/show-rally-crew-details/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            
	     $row[] = $options;
	     
	     $rows[] = $row;
         endforeach;
         
         
         $response = array(
            "aaData" => $rows,
            "sEcho" => (int)$_REQUEST['sEcho'],
            "iTotalRecords" => $results['totalRecords'],
            "iTotalDisplayRecords" => $results['totalRecords']
        );
       
        echo json_encode($response);
     }
     
     public function showRallyStages(){
	 
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams']['id'],'id');
	
	$this->view->assign('rally',$rally);
     }
     
     public function showRallyStagesData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('rally', 'rally');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Rally_Model_Doctrine_Stage', 
            'class' => 'Rally_DataTables_Stage', 
            'fields' => array('s.id','s.name','s.length','s.min_time'),
        ));
	 
         $iTotalRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['name'];
             $row[] = $result['length'];
             $row[] = $result['min_time'];
             $options = '<a href="/admin/rally/show-rally-crew-details/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            $options .= '<a href="/admin/rally/calculate-stage-time/rally-id/'.$result['rally_id'].'/stage-id/'.$result['id'].'" class="btn btn-xs blue"><i class="fa fa-users"></i> Calculate</a>';
            
	     $row[] = $options;
	     
	     $rows[] = $row;
         endforeach;
         
	  $response = array(
            "aaData" => $rows,
            "sEcho" => (int)$_REQUEST['sEcho'],
            "iTotalRecords" => $results['totalRecords'],
            "iTotalDisplayRecords" => $results['totalRecords']
        );
       
        echo json_encode($response);
     }
     
     public function addStage(){
	 
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams']['rally-id'],'id');
	
	
	$form = new Form();
	$form->addClass('form-horizontal');
        $name = $form->createElement('text','name',array(),'Nazwa');
	$name->addAdminDefaultClasses();
        $length = $form->createElement('text','length',array(),'Długość');
	$length->addAdminDefaultClasses();
        $min_time = $form->createElement('select','surface1',array(),'Minimalny czas');
	$min_time->addAdminDefaultClasses();
	
	if(isset($_POST['form_send'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
		$values['rally_id'] = $rally['id'];
		$stage = $rallyService->saveStage($values);
		
		if(isset($_POST['submit']))
		    TK_Helper::redirect('/admin/rally/show-rally-stages/id/'.$rally['id']);
		
		if(isset($_POST['save_and_add_new']))
		    TK_Helper::redirect('/admin/rally/add-stage/id/'.$rally['id']);
		
		if(isset($_POST['save_and_stay']))
		    TK_Helper::redirect('/admin/rally/edit-stage/id/'.$rally['id'].'/stage-id/'.$stage['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
	    
	}
	
	$this->view->assign('form',$form);
	$this->view->assign('rally',$rally);
     }
     
     public function addRally(){
	 
        $rallyService = parent::getService('rally','rally');
	
	$surfaces = $rallyService->getAllSurfaces(Doctrine_Core::HYDRATE_ARRAY);

	$form = new Form();
	$form->addClass('form-horizontal');
        $name = $form->createElement('text','name',array(),'Nazwa');
	$name->addAdminDefaultClasses();
        $date = $form->createElement('text','date',array('calendar' => true),'Data');
	$date->addAdminDefaultClasses();
	$date->addParam('readonly', '');
	$date->addParam('size', '16');
        $min_time = $form->createElement('text','min_time',array(),'Minimalny czas');
	$min_time->addAdminDefaultClasses();
	$min_time->addClass('timePicker');
	$surface1 = $form->createElement('select','surface1',array(),'Nawierzchnia 1');
	$surface1->addMultiOptions($surfaces,true);
	$surface1->addClass('form-control');
	$surface2 = $form->createElement('select','surface2',array(),'Nawierzchnia 2');
	$surface2->addMultiOptions($surfaces,true);
	$surface2->addClass('form-control');
	$surface3 = $form->createElement('select','surface3',array(),'Nawierzchnia 3');
	$surface3->addMultiOptions($surfaces,true);
	$surface3->addClass('form-control');
        $percent1 = $form->createElement('text','percent1',array());
	$percent1->addAdminDefaultClasses();
	$percent1->addClass('input-xsmall');
        $percent2 = $form->createElement('text','percent2',array());
	$percent2->addAdminDefaultClasses();
	$percent2->addClass('input-xsmall');
        $percent3 = $form->createElement('text','percent3',array());
	$percent3->addAdminDefaultClasses();
	$percent3->addClass('input-xsmall');
	
        $active = $form->createElement('checkbox','active',array());
	$active->addAdminDefaultClasses();
	$active->addParam('checked', 'checked');
	
	
	if(isset($_POST['form_send'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		
		$rally = $rallyService->saveRally($values);
		
		if(isset($_POST['submit']))
		    TK_Helper::redirect('/admin/rally/list-rally');
		
		if(isset($_POST['save_and_add_new']))
		    TK_Helper::redirect('/admin/rally/add-rally');
		
		if(isset($_POST['save_and_stay']))
		    TK_Helper::redirect('/admin/rally/edit-rally/id/'.$rally['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
	    
	}
	
	$this->view->assign('form',$form);
     }
     
     public function calculateStageTime(){
	 
         $view = $this->view;
         $view->setNoRender();
	 
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $crews = $rallyService->getRallyCrews($GLOBALS['urlParams']['rally-id'],'rally_id',Doctrine_Core::HYDRATE_RECORD);
	$stage = $rallyService->getStageShort($GLOBALS['urlParams']['stage-id'],'id',Doctrine_Core::HYDRATE_ARRAY);
	// get array with id of crews which's time hasn't been calculated yet
	$crewsWithResults = $rallyService->getCrewsWithoutResults($stage['id'],Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$surfaces = $rallyService->getRallySurfaces($GLOBALS['urlParams']['rally-id'],Doctrine_Core::HYDRATE_ARRAY);
        
        $carService = parent::getService('car','car');
        $trainingService = parent::getService('people','training');
        $peopleService = parent::getService('people','people');
	$peopleService->runStageForCrew($stage,$crews,$crewsWithResults,$surfaces);
	
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        
	TK_Helper::redirect('/admin/rally/show-stage-result/id/'.$stage['id']);
    }
    
    public function calculateTraining(){
        $view = $this->view;
        $view->setNoRender();
        
        
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
        Service::loadModels('car', 'car');
        $rallyService = parent::getService('rally','rally');
        $trainingService = parent::getService('people','training');
        
        $crews = $rallyService->getCrewsWithNotCompletedTrainingToday(Doctrine_Core::HYDRATE_ARRAY);
        $trainingService->calculateTraining($crews,$rallyService);
        
	TK_Helper::redirect('/admin/rally/show-training-results/');
    }
     
    public function showTrainingResults(){
        
//        Service::loadModels('people', 'people');
//        $trainingService = parent::getService('people','training');
//        
//        $trainingResults = $trainingService->getAllTodayTrainingResults();
//        $this->view->assign('trainingResults',$trainingResults);
    }
    
    public function showTrainingResultsData(){
        $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
        Service::loadModels('rally', 'rally');
        Service::loadModels('team', 'team');
        Service::loadModels('people', 'people');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'People_Model_Doctrine_Training', 
            'class' => 'People_DataTables_Training', 
            'fields' => array('p.id','p.last_name','te.name'),
        ));
	 
         $iTotalRecords = count($results);
         $rows = array();
         foreach($results as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['people_id'].'">';
             $row[] = $result['people_id'];
             $row[] = $result['People']['last_name']." ".$result['People']['first_name'];
             $row[] = $result['People']['Team']['name'];
             $row[] = $result['skill_name'];
             $row[] = $result['current_skill_level'];
             
             if($result['skill_promotion'])
                 $row[] = '<span class="label label-sm label-success">Nowa gwiazdka</span>';
             else
                 $row[] = '';
             
             $row[] = round($result['km_passed_today']/$result['max_available_km_passed_today'],2);
//             $options = '<a href="/admin/rally/show-rally-crew-details/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
//            $options .= '<a href="/admin/rally/calculate-stage-time/rally-id/'.$result['rally_id'].'/stage-id/'.$result['id'].'" class="btn btn-xs blue"><i class="fa fa-users"></i> Calculate</a>';
//            
//	     $row[] = $options;
	     
	     $rows[] = $row;
         endforeach;
         
	  $iDisplayLength = intval($_REQUEST['iDisplayLength']);
	  $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
	  $sEcho = intval($_REQUEST['sEcho']);
         
         $response = array(
            "aaData" => $rows,
            "sEcho" => $sEcho,
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
        );
       
        echo json_encode($response,JSON_UNESCAPED_SLASHES);
    }
}


?>
