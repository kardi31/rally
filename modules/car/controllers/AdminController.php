<?php

class Car_Admin extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    public function listCar(){}
     
     
     public function listCarData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('car', 'car');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Car_Model_Doctrine_CarModels', 
            'class' => 'Car_DataTables_CarModel', 
            'fields' => array('cm.id','cm.name','cm.capacity','cm.horsepower','cm.max_speed','cm.acceleration','cm.league','cm.on_market','cm.price'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = '<img src="/media/cars/'.$result['photo'].'" style="width:40px;margin-right:20px;" />'.$result['name'];
             $row[] = $result['capacity'];
             $row[] = $result['horsepower'];
             $row[] = $result['max_speed'];
             $row[] = $result['acceleration'];
             $row[] = $result['league'];
             
             if($result['on_market'])
                 $row[] = '<a href="/admin/car/put-on-market/'.$result['id'].'"><span class="label label-sm label-success">Tak</span></a>';
             else
                 $row[] = '<a href="/admin/car/put-on-market/'.$result['id'].'"><span class="label label-sm label-danger">Nie</span></a>';
             
             $row[] = $result['price'];
             
//	     
             $options = '<a href="/admin/car/edit-car/'.$result['id'].'" class="btn btn-xs blue"><i class="fa fa-edit"></i> Edit</a>';
             $options .='<a href="/admin/car/remove-car/'.$result['id'].'" class="btn default btn-xs red"><i class="fa fa-times"></i> Remove </a>';
//             if($result['finished']){
//                $options .='<a href="/admin/rally/show-rally-result/id/'.$result['id'].'" class="btn default btn-xs green"><i class="fa fa-list"></i> Results </a>';
//                $options .='<a href="/admin/rally/show-rally-detailed-result/id/'.$result['id'].'" class="btn default btn-xs red"><i class="fa fa-list"></i> Detailed results </a>';
//             }
//             $options .='<a href="/admin/rally/edit-rally/id/'.$result['id'].'" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Edit </a>';
//	     
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
     
     public function putOnMarket(){
         $carService = parent::getService('car','car');
        
        if(!$carModel = $carService->getCarModel($GLOBALS['urlParams'][1],'id')){
            echo "brak modelu";exit;
        }
        
        if($carModel->get('active')){
            $carModel->set('active',0);
        }
        else{
            $carModel->set('active',1);
        }
        $carModel->save();
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
    
     
     public function addCar(){
         $carService = parent::getService('car','car');
         
         $form = $this->getForm('car','car');
        
         if(isset($_POST['submit'])||isset($_POST['save_and_add_new'])||isset($_POST['save_and_stay'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		$carModel = $carService->saveCarModel($values);
		
		if(isset($_POST['submit']))
		    TK_Helper::redirect('/admin/car/list-car');
		
		if(isset($_POST['save_and_add_new']))
		    TK_Helper::redirect('/admin/car/add-car/');
		
		if(isset($_POST['save_and_stay']))
		    TK_Helper::redirect('/admin/car/car-sponsor/'.$carModel['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
	    
	}
         
        $this->view->assign('form',$form);
     }
     
     public function editCar(){
         $carService = parent::getService('car','car');
         
         if(!$carModel = $carService->getCarModel($GLOBALS['urlParams'][1])){
             echo "nie istnieje model";exit;
         }
         
         $form = $this->getForm('car','car');
         $form->populate($carModel->toArray());
        
         if(isset($_POST['submit'])||isset($_POST['save_and_add_new'])||isset($_POST['save_and_stay'])){
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
                
                $values = $_POST;
		$carModel = $carService->saveCarModel($values);
		
		if(isset($_POST['submit']))
		    TK_Helper::redirect('/admin/car/list-car');
		
		if(isset($_POST['save_and_add_new']))
		    TK_Helper::redirect('/admin/car/add-car/');
		
		if(isset($_POST['save_and_stay']))
		    TK_Helper::redirect('/admin/car/car-sponsor/'.$carModel['id']);
		
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
	    
	}
         
        $this->view->assign('carModel',$carModel);
        $this->view->assign('form',$form);
     }
     
     public function removeCar(){
         $carService = parent::getService('car','car');
         
         if(!$carModel = $carService->getCarModel($GLOBALS['urlParams'][1])){
             echo "nie istnieje model";exit;
         }
         
		Doctrine_Manager::getInstance()->getCurrentConnection()->beginTransaction();
        $carModel->delete();
                Doctrine_Manager::getInstance()->getCurrentConnection()->commit();
        TK_Helper::redirect('/admin/car/list-car');
     }
    
    // people finish
    
    // car start
    
}
?>
