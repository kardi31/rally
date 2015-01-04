<?php


class Captcha_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function index(){
       $view = $this->view;
         $view->setNoRender();
	 include(BASE_PATH.'/library/TK/Form/captcha/captcha.php');
    }
    
    
}
?>
