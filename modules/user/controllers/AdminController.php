<?php


/* 
 * @author = Tomasz Kardas <kardi31@o2.pl>
 * 
 */
class User_Admin extends Controller{
    
     public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
     public function login(){
     }
     
     public function listUser(){
         $this->view->assign('controller',$this);
     }
     
     public function listUserData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('user', 'user');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'User_Model_Doctrine_User', 
            'class' => 'User_DataTables_User', 
            'fields' => array('u.id','u.email','u.role','u.active'),
        ));
//         $columns = array('email','role','active');
//         $q = $table->createQuery('u');
//         $results = $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
         
         $iTotalRecords = count($results);
         $rows = array();
         foreach($results as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['email'];
             $row[] = $result['role'];
             if($result['active'])
                 $row[] = '<span class="label label-sm label-success">Aktywny</span>';
             else
                 $row[] = '<span class="label label-sm label-danger">Nieaktywny</span>';
             $row[] = '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-search"></i> View</a>';
             $rows[] = $row;
         endforeach;
         
   // $iDisplayStart = intval($_REQUEST['iDisplayStart']);
  
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
}
?>
