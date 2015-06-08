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
            'fields' => array('u.id','u.email','u.role','u.active','u.created_at','u.last_active'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['email'];
             $row[] = $result['role'];
             if($result['active'])
                 $row[] = '<a href="/admin/user/set-active/id/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/user/set-active/id/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             $row[] = $result['created_at'];
             $row[] = $result['last_active'];
             $options = '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-search"></i> View</a>';
//             $options .= '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-search"></i> View</a>';
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
     
     public function setActive(){
         $userService = parent::getService('user','user');
        
        if(!$user = $userService->getUser($GLOBALS['urlParams']['id'],'id')){
            echo "brak użytkownika";exit;
        }
        
        if($user->get('active')){
            $user->set('active',0);
        }
        else{
            $user->set('active',1);
        }
        $user->save();
        
        TK_Helper::redirect('/admin/user/list-user');
     }
}
?>
