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
                 $row[] = '<a href="/admin/user/set-active/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/user/set-active/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
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
        
        if(!$user = $userService->getUser($GLOBALS['urlParams'][1],'id')){
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
     
     public function listMessage(){
     }
     
     public function listMessageData(){
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('user', 'user');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'User_Model_Doctrine_Board', 
            'class' => 'User_DataTables_Board', 
            'fields' => array('b.id','b.user_id','b.writer_id','b.created_at'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'">';
             $row[] = $result['id'];
             $row[] = $result['User']['username'];
             $row[] = $result['Writer']['username'];
             $row[] = $result['message'];
             $row[] = $result['created_at'];
             $options = '<a href="/admin/user/delete-message/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-times"></i> Delete</a>';
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
     
     
     public function deleteMessage(){
         $messageService = parent::getService('user','message');
        
        if(!$message = $messageService->getMessage($GLOBALS['urlParams'][1],'id')){
            echo "brak wiadomosc";exit;
        }
        
        $message->delete();
        
        $message->save();
        
        TK_Helper::redirect('/admin/user/list-message');
     }
     
     public function sendActivationEmail(){
        $mailService = parent::getService('user','mail');
        $userService = parent::getService('user','user');
         $user = $userService->getUser($GLOBALS['urlParams'][1],'id');
         
        $mailService->sendMail($user['email'],'Your FastRally registration',$mailService::prepareRegistrationMail($user['token']));
         echo "good";exit;
     }
}
?>
