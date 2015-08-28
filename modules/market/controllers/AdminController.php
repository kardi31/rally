<?php

class Market_Admin extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    public function listPeopleTransfer(){}
     
     public function listPeopleFutureTransfer(){}
     
     public function listPeopleTransferData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('market', 'market');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Market_Model_Doctrine_Offer', 
            'class' => 'Market_DataTables_Offer', 
            'fields' => array('o.id',array('p.first_name','p.last_name','p.id'),array('t.name','t.id'),'o.asking_price','o.highest_bid','o.created_at','o.start_date','o.finish_date',array('bt.name','b.value','b.created_at'),'o.player_moved','o.canceled'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['Player']['first_name']." ".$result['Player']['last_name']." <br />".$result['Player']['id'];
             $row[] = $result['Player']['Team']['name']." <br />".$result['Player']['Team']['id'];
             $row[] = $result['Player']['value'];
             $row[] = $result['asking_price'];
             $row[] = $result['highest_bid'];
             
             if($result['active'])
                 $row[] = '<a href="/admin/market/set-active/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-active/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['start_date'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['finish_date'],'d/m/Y H:i');
             
             $bids = "";
             foreach($result['Bids'] as $bid):
                if($bid['user_ip']==$result['user_ip']){
                    $bids .= "<span class='label label-danger'>#".$bid['id']." ".$bid['Team']['name']." <br />".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." <br />".$bid['value']."</span><br />";
                }
                else{
                    $bids .= $bid['Team']['name']." ".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." ".$bid['value']."<br />";
                }
             endforeach;
             $row[] = $bids;
             
             if($result['player_moved'])
                 $row[] = '<a href="/admin/market/cancel-transfer/'.$result['id'].'"><span class="label label-sm label-success">Tak</span></a>';
             else
                 $row[] = '<a href="/admin/market/cancel-transfer/'.$result['id'].'"><span class="label label-sm label-danger">Nie</span></a>';
             
             if($result['canceled']){
                 $row[] = '<span class="label label-sm label-success">Canceled</span></a>';
             }
             else{
                 $row[] = "";
             }
             
             
//             if($result['active'])
//                 $row[] = '<span class="label label-sm label-success">Aktywny</span>';
//             else
//                 $row[] = '<span class="label label-sm label-danger">Nieaktywny</span>';
//	     
//             $options = '<a href="/admin/rally/show-rally-crews/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
//             $options .='<a href="/admin/rally/show-rally-stages/id/'.$result['id'].'" class="btn default btn-xs blue"><i class="fa fa-list"></i> Stages </a>';
//             if($result['finished']){
//                $options .='<a href="/admin/rally/show-rally-result/id/'.$result['id'].'" class="btn default btn-xs green"><i class="fa fa-list"></i> Results </a>';
//                $options .='<a href="/admin/rally/show-rally-detailed-result/id/'.$result['id'].'" class="btn default btn-xs red"><i class="fa fa-list"></i> Detailed results </a>';
//             }
//             $options .='<a href="/admin/rally/edit-rally/id/'.$result['id'].'" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Edit </a>';
//	     
//	     $row[] = $options;
	     $row[] = "";
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
         $marketService = parent::getService('market','market');
        
        if(!$offer = $marketService->getOffer($GLOBALS['urlParams'][1],'id')){
            echo "brak oferty";exit;
        }
        
        if($offer->get('active')){
            $offer->set('active',0);
        }
        else{
            $offer->set('active',1);
        }
        $offer->save();
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
    
     
     public function peopleDuplicate(){}
     
     public function peopleDuplicateData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('market', 'market');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Market_Model_Doctrine_PeopleDuplicate', 
            'class' => 'Market_DataTables_PeopleDuplicate', 
            'fields' => array('o.id',array('p.first_name','p.last_name','p.id'),array('t.name','t.id'),'p.value','o.asking_price','o.highest_bid',array('bt.name','b.value','b.created_at'),'o.user_ip','b.user_ip','pd.solved','pd.created_at'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['Offer']['Player']['first_name']." ".$result['Offer']['Player']['last_name']." <br />".$result['Offer']['Player']['id'];
             $row[] = $result['Offer']['Player']['Team']['name']." <br />".$result['Offer']['Player']['Team']['id'];
             $row[] = $result['Offer']['Player']['value'];
             $row[] = $result['Offer']['asking_price'];
             
             // if this offer won, color it to red
             // otherwise color green
             if($result['Bid']['value']==$result['Offer']['highest_bid']){
                 $highestBid = "<span class='label label-danger' style='display:block'>Top bid: ".$result['Offer']['highest_bid']."<br />Team bid : ".$result['Bid']['value']."</span>";
             }
             else{
                 $highestBid = "<span class='label block label-success' style='display:block'>Top bid: ".$result['Offer']['highest_bid']."<br />Team bid : ".$result['Bid']['value']."</span>";
             }
             $row[] = $highestBid;
             $row[] = $result['Bid']['Team']['name']." <br />".$result['Bid']['Team']['id'];
             $row[] = $result['Offer']['user_ip'];
             $row[] = $result['Bid']['user_ip'];
             
             if($result['solved'])
                 $row[] = '<a href="/admin/market/set-people-duplicate-solved/'.$result['id'].'"><span class="label label-sm label-success">Solved</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-people-duplicate-solved/'.$result['id'].'"><span class="label label-sm label-danger">Unsolved</span></a>';
             
             
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             
              if($result['Bid']['active'])
                 $row[] = '<a href="/admin/market/set-bid-active/'.$result['Bid']['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-bid-active/'.$result['Bid']['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
           
             
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
    
     public function setPeopleDuplicateSolved(){
         $duplicateService = parent::getService('market','duplicate');
        
        if(!$duplicate = $duplicateService->getDuplicatePeople($GLOBALS['urlParams'][1],'id')){
            echo "brak duplikatu zawodnika";exit;
        }
        
        if($duplicate->get('solved')){
            $duplicate->set('solved',0);
        }
        else{
            $duplicate->set('solved',1);
        }
        $duplicate->save();
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
     
     
    public function listPeopleIllegalTransfer(){}
     
     
     public function listPeopleIllegalTransferData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('market', 'market');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Market_Model_Doctrine_Offer', 
            'class' => 'Market_DataTables_OfferIllegal', 
            'fields' => array('o.id',array('p.first_name','p.last_name','p.id'),array('t.name','t.id'),'o.value','o.asking_price','o.highest_bid','o.active','o.created_at','o.start_date','o.finish_date',array('bt.name','b.value','b.created_at'),'b.active'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['Player']['first_name']." ".$result['Player']['last_name']." <br />".$result['Player']['id'];
             $row[] = $result['Player']['Team']['name']." <br />".$result['Player']['Team']['id'];
             $row[] = $result['Player']['value'];
             $row[] = $result['asking_price'];
             $row[] = $result['highest_bid'];
             
             if($result['active'])
                 $row[] = '<a href="/admin/market/set-active/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-active/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['start_date'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['finish_date'],'d/m/Y H:i');
             
             $bids = "";
             foreach($result['Bids'] as $bid):
                if($bid['user_ip']==$result['user_ip']){
                    $bids .= "<span class='label label-danger'>#".$bid['id']." ".$bid['Team']['name']." ".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." ".$bid['value']."</span><br />";
                }
                else{
                    $bids .= $bid['Team']['name']." ".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." ".$bid['value']."<br />";
                }
             endforeach;
             
             $row[] = $bids;
             
             if($result['Bids'][0]['active'])
                 $row[] = '<a href="/admin/market/set-bid-active/'.$result['Bids'][0]['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-bid-active/'.$result['Bids'][0]['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             
//             $options = '<a href="/admin/rally/show-rally-crews/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
//             $options .='<a href="/admin/rally/show-rally-stages/id/'.$result['id'].'" class="btn default btn-xs blue"><i class="fa fa-list"></i> Stages </a>';
//             if($result['finished']){
//                $options .='<a href="/admin/rally/show-rally-result/id/'.$result['id'].'" class="btn default btn-xs green"><i class="fa fa-list"></i> Results </a>';
//                $options .='<a href="/admin/rally/show-rally-detailed-result/id/'.$result['id'].'" class="btn default btn-xs red"><i class="fa fa-list"></i> Detailed results </a>';
//             }
//             $options .='<a href="/admin/rally/edit-rally/id/'.$result['id'].'" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Edit </a>';
//	     
//	     $row[] = $options;
	     $row[] = "";
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
     
      public function setBidActive(){
         $marketService = parent::getService('market','market');
        
        if(!$bid = $marketService->getBid($GLOBALS['urlParams'][1],'id')){
            echo "brak ofery zawodnika";exit;
        }
        
        if($bid->get('active')){
            $bid->set('active',0);
        }
        else{
            $bid->set('active',1);
        }
        $bid->save();
        
        $marketService->calculateHighestBid($bid['Offer']['id']);
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
     
     public function cancelTransfer(){
        
         
        // 1. Usuniecie wplaty z kupujacego
        // 2. Usuniecie wplyniecia na konto sprzedajacego
        // 3. POwrotna zmiana teamu
        // 4. Ustawienie oferty na canceled
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        
        $offer = $marketService->getFullOfferAndBid($GLOBALS['urlParams'][1]);
            $bid = $offer['Bids'][0];
            $teamService->removePreviousTeamMoney($bid['team_id'],$bid['value'],'Arrival of player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
            $teamService->removePreviousTeamMoney($offer['team_id'],$bid['value'],'Sell of player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
           
            $peopleService->changePersonTeam($offer['Player']['id'],$offer['team_id']);
            $offer->set('canceled',1);
            $offer->save();
            
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
    
    // people finish
    
    // car start
    
    public function listCarTransfer(){}
     
     public function listCarFutureTransfer(){}
     
     public function listCarTransferData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('market', 'market');
         Service::loadModels('people', 'people');
         Service::loadModels('team', 'team');
         Service::loadModels('car', 'car');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Market_Model_Doctrine_CarOffer', 
            'class' => 'Market_DataTables_CarOffer', 
            'fields' => array('o.id',array('c.name','m.name'),array('t.name','t.id'),'o.asking_price','o.highest_bid','o.created_at','o.start_date','o.finish_date',array('bt.name','b.value','b.created_at'),'o.player_moved','o.canceled'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['Car']['name']." ".$result['Car']['Model']['name']." <br />".$result['Car']['id'];
             $row[] = $result['Car']['Team']['name']." <br />".$result['Car']['Team']['id'];
             $row[] = $result['Car']['value'];
             $row[] = $result['asking_price'];
             $row[] = $result['highest_bid'];
             
             if($result['active'])
                 $row[] = '<a href="/admin/market/set-car-active/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-car-active/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['start_date'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['finish_date'],'d/m/Y H:i');
             
             $bids = "";
             foreach($result['Bids'] as $bid):
                if($bid['user_ip']==$result['user_ip']){
                    $bids .= "<span class='label label-danger'>#".$bid['id']." ".$bid['Team']['name']." <br />".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." <br />".$bid['value']."</span><br />";
                }
                else{
                    $bids .= $bid['Team']['name']." ".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." ".$bid['value']."<br />";
                }
             endforeach;
             $row[] = $bids;
             
             if($result['car_moved'])
                 $row[] = '<a href="/admin/market/cancel-car-transfer/'.$result['id'].'"><span class="label label-sm label-success">Tak</span></a>';
             else
                 $row[] = '<a href="/admin/market/cancel-car-transfer/'.$result['id'].'"><span class="label label-sm label-danger">Nie</span></a>';
             
             if($result['canceled']){
                 $row[] = '<span class="label label-sm label-success">Canceled</span></a>';
             }
             else{
                 $row[] = "";
             }
	     $row[] = "";
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
     
     public function setCarActive(){
         $marketService = parent::getService('market','market');
        
        if(!$offer = $marketService->getCarOffer($GLOBALS['urlParams'][1],'id')){
            echo "brak oferty";exit;
        }
        
        if($offer->get('active')){
            $offer->set('active',0);
        }
        else{
            $offer->set('active',1);
        }
        $offer->save();
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
    
     
     public function carDuplicate(){}
     
     public function carDuplicateData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('market', 'market');
         Service::loadModels('car', 'car');
         Service::loadModels('team', 'team');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Market_Model_Doctrine_CarDuplicate', 
            'class' => 'Market_DataTables_CarDuplicate', 
            'fields' => array('o.id',array('p.first_name','p.last_name','p.id'),array('t.name','t.id'),'p.value','o.asking_price','o.highest_bid',array('bt.name','b.value','b.created_at'),'o.user_ip','b.user_ip','pd.solved','pd.created_at'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['Offer']['Car']['name']." ".$result['Offer']['Car']['Model']['name']." <br />".$result['Offer']['Car']['id'];
             $row[] = $result['Offer']['Car']['Team']['name']." <br />".$result['Offer']['Car']['Team']['id'];
             $row[] = $result['Offer']['Car']['value'];
             $row[] = $result['Offer']['asking_price'];
             
             // if this offer won, color it to red
             // otherwise color green
             if($result['Bid']['value']==$result['Offer']['highest_bid']){
                 $highestBid = "<span class='label label-danger' style='display:block'>Top bid: ".$result['Offer']['highest_bid']."<br />Team bid : ".$result['Bid']['value']."</span>";
             }
             else{
                 $highestBid = "<span class='label block label-success' style='display:block'>Top bid: ".$result['Offer']['highest_bid']."<br />Team bid : ".$result['Bid']['value']."</span>";
             }
             $row[] = $highestBid;
             $row[] = $result['Bid']['Team']['name']." <br />".$result['Bid']['Team']['id'];
             $row[] = $result['Offer']['user_ip'];
             $row[] = $result['Bid']['user_ip'];
             
             if($result['solved'])
                 $row[] = '<a href="/admin/market/set-car-duplicate-solved/'.$result['id'].'"><span class="label label-sm label-success">Solved</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-car-duplicate-solved/'.$result['id'].'"><span class="label label-sm label-danger">Unsolved</span></a>';
             
             
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             
              if($result['Bid']['active'])
                 $row[] = '<a href="/admin/market/set-car-bid-active/'.$result['Bid']['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-car-bid-active/'.$result['Bid']['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
           
             
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
    
     public function setCarDuplicateSolved(){
         $duplicateService = parent::getService('market','duplicate');
        
        if(!$duplicate = $duplicateService->getDuplicateCar($GLOBALS['urlParams'][1],'id')){
            echo "brak duplikatu zawodnika";exit;
        }
        
        if($duplicate->get('solved')){
            $duplicate->set('solved',0);
        }
        else{
            $duplicate->set('solved',1);
        }
        $duplicate->save();
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
     
     
    public function listCarIllegalTransfer(){}
     
     
     public function listCarIllegalTransferData(){
         
         $view = $this->view;
         $view->setNoRender();
         $view->requireDTFactory();
         Service::loadModels('market', 'market');
         Service::loadModels('car', 'car');
         Service::loadModels('team', 'team');
         
         $results = $dataTables = Index_DataTables_Factory::factory(array(
            'table' => 'Market_Model_Doctrine_CarOffer', 
            'class' => 'Market_DataTables_CarOfferIllegal', 
            'fields' => array('o.id',array('p.first_name','p.last_name','p.id'),array('t.name','t.id'),'o.value','o.asking_price','o.highest_bid','o.active','o.created_at','o.start_date','o.finish_date',array('bt.name','b.value','b.created_at'),'b.active'),
        ));
         
         $iTotalDisplayRecords = count($results['query']);
         $rows = array();
         foreach($results['query'] as $result):
             $row = array();
             $row[] = '<input type="checkbox" name="id[]" value="'.$result['id'].'" />';
             $row[] = $result['id'];
             $row[] = $result['Car']['name']." ".$result['Car']['Model']['name']." <br />".$result['Car']['id'];
             $row[] = $result['Car']['Team']['name']." <br />".$result['Car']['Team']['id'];
             $row[] = $result['Car']['value'];
             $row[] = $result['asking_price'];
             $row[] = $result['highest_bid'];
             
             if($result['active'])
                 $row[] = '<a href="/admin/market/set-car-active/'.$result['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-car-active/'.$result['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             
             $row[] = TK_Text::timeFormat($result['created_at'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['start_date'],'d/m/Y H:i');
             $row[] = TK_Text::timeFormat($result['finish_date'],'d/m/Y H:i');
             
             $bids = "";
             foreach($result['Bids'] as $bid):
                if($bid['user_ip']==$result['user_ip']){
                    $bids .= "<span class='label label-danger'>#".$bid['id']." ".$bid['Team']['name']." ".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." ".$bid['value']."</span><br />";
                }
                else{
                    $bids .= $bid['Team']['name']." ".TK_Text::timeFormat($bid['created_at'],'d/m/Y H:i')." ".$bid['value']."<br />";
                }
             endforeach;
             
             $row[] = $bids;
             
             if($result['Bids'][0]['active'])
                 $row[] = '<a href="/admin/market/set-car-bid-active/'.$result['Bids'][0]['id'].'"><span class="label label-sm label-success">Aktywny</span></a>';
             else
                 $row[] = '<a href="/admin/market/set-car-bid-active/'.$result['Bids'][0]['id'].'"><span class="label label-sm label-danger">Nieaktywny</span></a>';
             
//             $options = '<a href="/admin/rally/show-rally-crews/id/'.$result['id'].'" class="btn btn-xs default"><i class="fa fa-users"></i> Crews</a>';
//             $options .='<a href="/admin/rally/show-rally-stages/id/'.$result['id'].'" class="btn default btn-xs blue"><i class="fa fa-list"></i> Stages </a>';
//             if($result['finished']){
//                $options .='<a href="/admin/rally/show-rally-result/id/'.$result['id'].'" class="btn default btn-xs green"><i class="fa fa-list"></i> Results </a>';
//                $options .='<a href="/admin/rally/show-rally-detailed-result/id/'.$result['id'].'" class="btn default btn-xs red"><i class="fa fa-list"></i> Detailed results </a>';
//             }
//             $options .='<a href="/admin/rally/edit-rally/id/'.$result['id'].'" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Edit </a>';
//	     
//	     $row[] = $options;
	     $row[] = "";
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
     
      public function setCarBidActive(){
         Service::loadModels('team', 'team');
         $marketService = parent::getService('market','market');
        
        if(!$bid = $marketService->getCarBid($GLOBALS['urlParams'][1],'id')){
            echo "brak ofery zawodnika";exit;
        }
        
        if($bid->get('active')){
            $bid->set('active',0);
        }
        else{
            $bid->set('active',1);
        }
        $bid->save();
        
        $marketService->calculateHighestBid($bid['CarOffer']['id']);
        
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
     }
     
     public function cancelCarTransfer(){
        
         
        // 1. Usuniecie wplaty z kupujacego
        // 2. Usuniecie wplyniecia na konto sprzedajacego
        // 3. POwrotna zmiana teamu
        // 4. Ustawienie oferty na canceled
        $peopleService = parent::getService('people','people');
        $teamService = parent::getService('team','team');
        $marketService = parent::getService('market','market');
        
        $offer = $marketService->getFullOfferAndBid($GLOBALS['urlParams'][1]);
            $bid = $offer['Bids'][0];
            $teamService->removePreviousTeamMoney($bid['team_id'],$bid['value'],'Arrival of player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
            $teamService->removePreviousTeamMoney($offer['team_id'],$bid['value'],'Sell of player '.$offer['Player']['first_name']." ".$offer['Player']['last_name']);
           
            $peopleService->changePersonTeam($offer['Player']['id'],$offer['team_id']);
            $offer->set('canceled',1);
            $offer->save();
            
        TK_Helper::redirect($_SERVER['HTTP_REFERER']);
    }
}
?>
