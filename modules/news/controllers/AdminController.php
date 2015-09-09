<?php

class Market_Admin extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    
    public function addNews(){
        $newsService = parent::getService('news','news');
        
        $form = $this->getForm('news','news');
        $marketService->calculateNewValuesForAllPlayers($season);
        echo "good";exit;
        
    }
    
    
    
}
?>
