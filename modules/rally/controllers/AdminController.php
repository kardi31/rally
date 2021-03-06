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
             
             
             $options = '<a href="/admin/rally/show-rally-crews/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
             $options .='<a href="/admin/rally/show-rally-stages/'.$result['id'].'" class="btn default btn-xs blue"><i class="fa fa-list"></i> Stages </a>';
             if($result['finished']){
                $options .='<a href="/admin/rally/show-rally-result/'.$result['id'].'" class="btn default btn-xs green"><i class="fa fa-list"></i> Results </a>';
                $options .='<a href="/admin/rally/show-rally-detailed-result/'.$result['id'].'" class="btn default btn-xs red"><i class="fa fa-list"></i> Detailed results </a>';
             }
             $options .='<a href="/admin/rally/edit-rally/'.$result['id'].'" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Edit </a>';
	     
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
             $options = '<a href="/admin/rally/show-rally-crew-details/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            
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
	 
	$stage_id = $GLOBALS['urlParams'][1];
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
             $row[] = $result['Crew']['Team']['name']."<br />Crew id #".$result['Crew']['id'];
             $row[] = $result['base_time'];
	     if(isset($result['Accident'])){
                $row[] = $result['Accident']['name']."<br /> ".$result['Accident']['damage']; 
	     }
            else {
                $row[] = '';
            }
            $row[] = '';
//             $row[] = $result['Crew']['risk'];
//             $row[] = $result['Crew']['Driver']['last_name']." ".$result['Crew']['Driver']['first_name'];
//             $row[] = $result['Crew']['Pilot']['last_name']." ".$result['Crew']['Pilot']['first_name'];
//             $row[] = $result['Crew']['Car']['name'];
//             if(!$result['out_of_race'])
//                 $row[] = '<span class="label label-sm label-success">W wyścigu</span>';
//             else
//                 $row[] = '<span class="label label-sm label-danger">Poza trasą</span>';
	     
//             $options = '<a href="/admin/rally/show-rally-crew-details/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
//            
//	     $row[] = $options;
//	     
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
	 
	$rally_id = $GLOBALS['urlParams'][1];
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
             
             $options = '<a href="/admin/rally/show-rally-crew-details/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            
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
     
     public function showRallyDetailedResult(){
	 
	$rally_id = $GLOBALS['urlParams'][1];
        $rallyService = parent::getService('rally','rally');
	$rally = $rallyService->getRally($rally_id,'id',Doctrine_Core::HYDRATE_RECORD);
        
	$this->view->assign('rally',$rally);
     }
     
     public function showRallyDetailedResultData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('rally', 'rally');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         Service::loadModels('car', 'car');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Rally_Model_Doctrine_RallyResult', 
            'class' => 'Rally_DataTables_RallyDetailedResult', 
            'fields' => array('r.id','t.name','d.composure','p.composure','m.name','c.risk','r.total_time','r.out_of_race','r.stage_out_number'),
        ));
	 
         $iTotalRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             
             $driverData = " Composure: ".$result['Crew']['Driver']['composure']."<br />";
             $driverData .= " Speed: ".$result['Crew']['Driver']['speed']."<br />";
             $driverData .= " Regularity: ".$result['Crew']['Driver']['regularity']."<br />";
             $driverData .= " On gravel: ".$result['Crew']['Driver']['on_gravel']."<br />";
             $driverData .= " On tarmac: ".$result['Crew']['Driver']['on_tarmac']."<br />";
             $driverData .= " On snow: ".$result['Crew']['Driver']['on_snow']."<br />";
             $driverData .= " In rain: ".$result['Crew']['Driver']['in_rain']."<br />";
             $driverData .= " Form: ".$result['Crew']['Driver']['form']."<br />";
             $driverData .= " Talent: ".$result['Crew']['Driver']['talent'];
             
             $pilotData = " Composure: ".$result['Crew']['Pilot']['composure']."<br />";
             $pilotData .= " Dictate rhytm: ".$result['Crew']['Pilot']['dictate_rhytm']."<br />";
             $pilotData .= " Diction: ".$result['Crew']['Pilot']['diction']."<br />";
             $pilotData .= " Route description: ".$result['Crew']['Pilot']['route_description']."<br />";
             $pilotData .= " Intelligence: ".$result['Crew']['Pilot']['intelligence']."<br />";
             $pilotData .= " Form: ".$result['Crew']['Pilot']['form']."<br />";
             $pilotData .= " Talent: ".$result['Crew']['Pilot']['talent'];
             
             $carData = " Model: ".$result['Crew']['Car']['Model']['name']."<br />";
             $carData .= " Capacity: ".$result['Crew']['Car']['Model']['capacity']."<br />";
             $carData .= " Horse power: ".$result['Crew']['Car']['Model']['horsepower']."<br />";
             $carData .= " Max speed: ".$result['Crew']['Car']['Model']['max_speed']."<br />";
             $carData .= " Acceleration: ".$result['Crew']['Car']['Model']['acceleration']."<br />";
             $carData .= " Mileage: ".$result['Crew']['Car']['mileage']."<br />";
             
             $row[] = $result['Crew']['Team']['name']."<br />".$result['Crew']['team_id'];
             $row[] = $driverData;
             $row[] = $pilotData;
             $row[] = $carData;
             $row[] = $result['Crew']['risk'];
             $row[] = $result['total_time'];
             if(!$result['out_of_race']){
                 $row[] = '<span class="label label-sm label-success">W wyścigu</span>';
                 $row[] = $result['stage_out_number'];
             }
             else{
                 $row[] = '<span class="label label-sm label-danger">Poza trasą</span>';
                 $row[] = $result['stage_out_number'];
             }
             
             
             
             $options = '<a href="/admin/rally/show-rally-crew-details/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            
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
        $rally = $rallyService->getRally($GLOBALS['urlParams'][1],'id');
	
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
             $options = '<a href="/admin/rally/show-rally-crew-details/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
            $options .= '<a href="/admin/rally/calculate-stage-time/'.$result['rally_id'].'/'.$result['id'].'" class="btn btn-xs blue"><i class="fa fa-users"></i> Calculate</a>';
            
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
        $rally = $rallyService->getRally($GLOBALS['urlParams'][1],'id');
	
	
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

	$form = $this->getForm('rally','AddRally');
	
        $form->getElement('surface1')->addMultiOptions($surfaces,true);
        $form->getElement('surface2')->addMultiOptions($surfaces,true);
        $form->getElement('surface3')->addMultiOptions($surfaces,true);
	
	if(isset($_POST['form_send'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		$values['league_rally'] = 0;
		$rally = $rallyService->saveRally($values);
                for($i=0;$i<17;$i++){
                    $rallyService->saveRallyStage($rally,$values['stage_name'][$i],$values['stage_length'][$i],$i);
                }
                
		
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
     
     
     public function addBigRally(){
	 
        $rallyService = parent::getService('rally','rally');
        $carService = parent::getService('car','car');
	
        $cars = $carService->prepareCarModels();
	$surfaces = $rallyService->getAllSurfaces(Doctrine_Core::HYDRATE_ARRAY);

	$form = $this->getForm('rally','AddRally');
	
        $form->getElement('surface1')->addMultiOptions($surfaces,true);
        $form->getElement('surface2')->addMultiOptions($surfaces,true);
        $form->getElement('surface3')->addMultiOptions($surfaces,true);
        $form->getElement('award1')->addMultiOptions($cars,true);
        $form->getElement('award2')->addMultiOptions($cars,true);
        $form->getElement('award3')->addMultiOptions($cars,true);
	
	if(isset($_POST['form_send'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		$values['league_rally'] = 0;
		$values['big_awards'] = 1;
		$rally = $rallyService->saveRally($values);
                for($i=0;$i<17;$i++){
                    $rallyService->saveRallyStage($rally,$values['stage_name'][$i],$values['stage_length'][$i],$i);
                }
                
                for($i=1;$i<=3;$i++):
                    $bigAwardData = array('rally_id' => $rally['id']);
                    if(isset($values['award'.$i])&&!empty($values['award'.$i])){
                        $bigAwardData['award_type'] = 'car';
                        $bigAwardData['car_model_id'] = $values['award'.$i];
                    }
                    else{
                        $bigAwardData['award_type'] = 'premium';
                        $bigAwardData['premium'] = $values['award_premium'.$i];
                    }
                    $rallyService->saveBigAwards($bigAwardData);
                endfor;
                
		
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
     
     
     public function addLeagueRally(){
	 
        $rallyService = parent::getService('rally','rally');
	
	$surfaces = $rallyService->getAllSurfaces(Doctrine_Core::HYDRATE_ARRAY);

	$form = $this->getForm('rally','AddRally');
	
        $form->getElement('surface1')->addMultiOptions($surfaces,true);
        $form->getElement('surface2')->addMultiOptions($surfaces,true);
        $form->getElement('surface3')->addMultiOptions($surfaces,true);
	
	if(isset($_POST['form_send'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		$values['league_rally'] = 1;
		$rally = $rallyService->saveRally($values);
                for($i=0;$i<17;$i++){
                    $rallyService->saveRallyStage($rally,$values['stage_name'][$i],$values['stage_length'][$i],$i);
                }
                
		
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
        $crews = $rallyService->getRallyCrews($GLOBALS['urlParams'][1],'rally_id',Doctrine_Core::HYDRATE_RECORD);
	$stage = $rallyService->getStageShort($GLOBALS['urlParams'][2],'id',Doctrine_Core::HYDRATE_ARRAY);
	// get array with id of crews which's time hasn't been calculated yet
	$crewsWithResults = $rallyService->getCrewsWithoutResults($stage['id'],Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$surfaces = $rallyService->getRallySurfaces($GLOBALS['urlParams'][1],Doctrine_Core::HYDRATE_ARRAY);
        
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
    
    public function moveRalliesToData(){
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyService->getAllRallies();
        foreach($rallies as $rally):
            $rallyDataService->saveRallyDataFromRally($rally);
        endforeach;
        echo "good";exit;
    }
    
    public function changeRallyDate(){
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams'][1],'slug');
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                $newDate = strtotime("- 6 hours");
                $stage->set('date',date('Y-m-d H:i:s',$newDate));
                $stage->save();
            endforeach;
            $rally->set('date',date('Y-m-d H:i:s',$newDate));
            $rally->save();
        echo "good";exit;
    }
    
    
    public function changeRallyToDate(){
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rally = $rallyService->getRally($GLOBALS['urlParams'][1],'slug');
        $date = $GLOBALS['urlParams'][2]." 09:00:00";
            $stages = $rally->get('Stages');
            $rally->set('date',$date);
//            var_dump($date);exit;
            $rally->save();
//            var_dump($date);exit;
            foreach($stages as $key => $stage):
                $newMins = ($key)*15;
                $newDate = strtotime($date." + ".$newMins." minutes");
                $stage->set('date',date('Y-m-d H:i:s',$newDate));
                $stage->save();
            endforeach;
        echo "good";exit;
    }
    
    public function changeRalliesDate(){
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallies = $rallyService->getAllRallies();
        foreach($rallies as $rally):
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                $newDate = strtotime($rally['date']." +".($key*15)." mins");
                $stage->set('date',date('Y-m-d H:i:s',$newDate));
                $stage->save();
            endforeach;
        endforeach;
        echo "good";exit;
    }
    
    public function changeRallyWeekDay(){
        ini_set('max_execution_time',300000);
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyDataService->getAllRallies();
        $week = 1;
        $day = 1;
        foreach($rallies as $rally):
            $rally->set('day',$day);
            $rally->set('week',$week);
            $rally->save();
            $week++;
            if($week==13){
                $day++;
                $week = 1;
            }
        endforeach;
        echo "good";exit;
    }
    
    public function changeStageMinTime(){
        
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyService->getAllRallies();
        foreach($rallies as $rally):
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                $stage_length = $stage['length'];
                $timeMin = $stage_length / 87 * 60;
                $timeMax = $stage_length / 107 * 60;
                $randomTime = TK_Text::float_rand($timeMin,$timeMax,2);
                $seconds = $randomTime*60;
                $time = gmdate("H:i:s", $seconds);
                
                $stage->set('min_time',"00:".$randomTime);
                $stage->save();
            endforeach;
        endforeach;
        echo "good";exit;
        
    }
    
    public function changeStageDataMinTime(){
        
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyDataService->getAllRallies();
        foreach($rallies as $rally):
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                $stage_length = $stage['length'];
                $timeMin = $stage_length / 87 * 60;
                $timeMax = $stage_length / 107 * 60;
                $randomTime = TK_Text::float_rand($timeMin*60,$timeMax*60,2);
                $newDate = gmdate("H:i:s", $randomTime);
//                $newDate = date('i:s', strtotime(ceil($randomTime)." seconds"));
                
                $stage->set('min_time',$newDate);
                $stage->save();
            endforeach;
        endforeach;
        echo "good";exit;
        
    }
    
    public function changeStageLength(){
        
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyService->getAllRallies();
        foreach($rallies as $rally):
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                $stage_length = $stage['length'];
                
                if($stage2 = $rallyService->getRallyStage($rally,$stage['name']." 2")){
                    $stage2->set('length',$stage['length']);
                    $stage2->save();
                }
                
                if($stage3 = $rallyService->getRallyStage($rally,$stage['name']." 3")){
                    $stage3->set('length',$stage['length']);
                    $stage3->save();
                }
                
                
                if($stage4 = $rallyService->getRallyStage($rally,$stage['name']." 4")){
                    $stage4->set('length',$stage['length']);
                    $stage4->save();
                }
                
                
                if($stage5 = $rallyService->getRallyStage($rally,$stage['name']." 5")){
                    $stage5->set('length',$stage['length']);
                    $stage5->save();
                }
                
            endforeach;
        endforeach;
        die('1');
        
    }
    
    public function updateRallyStageFromData(){
        
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyDataService->getAllRallies();
        foreach($rallies as $rally):
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                
                if($stage2 = $rallyService->getRallyStage($rally,$stage['name'])){
                    $stage2->set('min_time',$stage['min_time']);
                    $stage2->save();
                }
                
                
            endforeach;
        endforeach;
        die('1');
        
    }
    
    
    public function changeStageDataLength(){
        
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rallyDataService = parent::getService('rally','rallyData');
        $rallies = $rallyDataService->getAllRallies();
        foreach($rallies as $rally):
            $stages = $rally->get('Stages');
            foreach($stages as $key => $stage):
                $stage_min_time = $stage['min_time'];
                if($stage2 = $rallyDataService->getRallyStage($rally,$stage['name']." 2")){
                    $stage2->set('min_time',$stage_min_time);
                    $stage2->save();
                }
                
                if($stage3 = $rallyDataService->getRallyStage($rally,$stage['name']." 3")){
                    $stage3->set('min_time',$stage_min_time);
                    $stage3->save();
                }
                
                
                if($stage4 = $rallyDataService->getRallyStage($rally,$stage['name']." 4")){
                    $stage4->set('min_time',$stage_min_time);
                    $stage4->save();
                }
                
                
                if($stage5 = $rallyDataService->getRallyStage($rally,$stage['name']." 5")){
                    $stage5->set('min_time',$stage_min_time);
                    $stage5->save();
                }
                
                if($stage6 = $rallyDataService->getRallyStage($rally,$stage['name']." II")){
                    $stage6->set('min_time',$stage_min_time);
                    $stage6->save();
                }
                
            endforeach;
        endforeach;
        die('1');
        
    }
    
    public function notFinish(){
        
        ini_set('max_execution_time',300000);
        $rallyService = parent::getService('rally','rally');
        $rally= $rallyService->getRally($GLOBALS['urlParams'][1]);
            $stages = $rally->get('Stages');
            foreach($stages as $stage):
                $stage->get('Results')->delete();
                $stage->set('finished',0);
                $stage->save();
            endforeach;
            $crews = $rally->get('Crews');
            foreach($crews as $crew):
                $crew->set('in_race',1);
                $crew->save();
            endforeach;
            $rally->set('finished',0);
            $rally->save();
            $rally->get('Results')->delete();
            $rally->save();
        die('1good');
        
        
    }
}


?>
