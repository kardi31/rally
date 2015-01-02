<?php
 
class Layout{
 
    protected $variables;
    public $content;
    public $layoutName;
    public $view;
    
    
    public function __construct() {
	$this->layoutName = 'layout';
    }
    
    public function render(){
        if(file_exists(BASE_PATH . '/layout/' . $this->layoutName .'.phtml')){
            include BASE_PATH . '/layout/' . $this->layoutName .'.phtml';
        }
            
    }
    
    public function setLayout($layout){
	$this->layoutName = $layout;
    }
    
    public function getLayout(){
	return $this->layoutName;
    }
    
    public function assign($name,$value){
        $this->variables[$name] = $value;
    }
 
    public function showFile($fileName){
	return $this->view->showFile($fileName);
    }
    
}