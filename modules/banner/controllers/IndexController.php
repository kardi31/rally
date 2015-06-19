<?php

class Banner_Index extends Controller{
 
    private static $instance = NULL;
    
    public function __construct(){
        parent::__construct();
    }
    
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new Banner_Index();
       return self::$instance;
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
        $this->view->assign('DD','wartosc');
        
    }
    
    public function showLeftBanner(){}
    public function showRightBanner(){}
    
    
    
}
?>
