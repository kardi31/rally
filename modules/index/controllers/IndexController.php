<?php

class Index_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        $this->actionStack($this, 'layoutHelper');
    }
    
    
    
}
?>
