<?php


/* 
 * @author = Tomasz Kardas <kardi31@o2.pl>
 * 
 */
class Forum_Admin extends Controller{
    
     public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
     
     public function listPost(){}
     
     public function listPostData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('user', 'user');
         Service::loadModels('forum', 'forum');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Forum_Model_Doctrine_Post', 
            'class' => 'Forum_DataTables_Post', 
            'fields' => array('p.id','t.name','p.content','p.created_at','u.name','p.active','p.moderator_notes','p.moderator_date','p.moderator_name'),
        ));
         $iTotalDisplayRecords = count($results['query']);
         
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
//         $row[] = '';    
         $row[] = $result['id'];
             $row[] = $result['Thread']['title'];
             $row[] = $result['content'];
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i:s');
//             $row[] = $result['Thread']['Category']['name'];
            
             $row[] = $result['User']['username'];
             if($result['active'])
                 $row[] = '<a href="/admin/forum/set-post-active/id/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span>';
             else
                 $row[] = '<a href="/admin/forum/set-post-active/id/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span>';
            
             $row[] = $result['moderator_notes'];
             $row[] = TK_Text::timeFormat($result['moderator_date'],'d/m/Y H:i:s');
             $row[] = $result['moderator_name'];
	     
	     $row[] = "";
	     
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
     
     public function listThread(){}
     
     public function listThreadData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('user', 'user');
         Service::loadModels('forum', 'forum');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Forum_Model_Doctrine_Thread', 
            'class' => 'Forum_DataTables_Thread', 
            'fields' => array('t.id','t.name','t.content','','t.created_at','t.active','t.moderator_notes','t.moderator_date','t.moderator_name'),
        ));
         $iTotalDisplayRecords = count($results['query']);
         
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
//         $row[] = '';    
         $row[] = $result['id'];
             $row[] = $result['title'];
             $row[] = $result['content'];
             $row[] = count($result['Posts']);
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i:s');
//             $row[] = $result['Thread']['Category']['name'];
            
             $row[] = $result['User']['username'];
             if($result['active'])
                 $row[] = '<a href="/admin/forum/set-thread-active/id/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span>';
             else
                 $row[] = '<a href="/admin/forum/set-thread-active/id/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span>';
            
             $row[] = $result['moderator_notes'];
             $row[] = TK_Text::timeFormat($result['moderator_date'],'d/m/Y H:i:s');
             $row[] = $result['moderator_name'];
	     
	     $row[] = "";
	     
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
     
     public function setThreadActive(){
        $this->view->setNoRender();
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $forumService = parent::getService('forum','forum');
        if(!$thread = $forumService->getThread($GLOBALS['urlParams']['id'],'id',Doctrine_Core::HYDRATE_RECORD)){
            echo "error";exit;
        }
        
       
        if($thread->get('active')){
            $thread->set('active',0);
        }
        else{
            $thread->set('active',1);
        }
            
        $thread->save();
        
	TK_Helper::redirect('/admin/forum/list-thread'); 
    }
    
     public function setPostActive(){
        $this->view->setNoRender();
        Service::loadModels('forum', 'forum');
        Service::loadModels('user', 'user');
        Service::loadModels('team', 'team');
	
        $forumService = parent::getService('forum','forum');
        if(!$post = $forumService->getPost($GLOBALS['urlParams']['id'],'id',Doctrine_Core::HYDRATE_RECORD)){
            echo "error";exit;
        }
        
       
        if($post->get('active')){
            $post->set('active',0);
        }
        else{
            $post->set('active',1);
        }
            
        $post->save();
        
	TK_Helper::redirect('/admin/forum/list-post'); 
    }
}


?>
