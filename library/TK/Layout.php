<?php
 
class Layout{
 
    protected $variables;
    public $content;
    public $layoutName;
    public $responseSegment;
    public $view;
    public $controller;
    private static $instance = NULL;
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new Layout();
       return self::$instance;
    }
    
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
    
    public function checkResponseSegments(){
        if($this->layoutName == 'layout'){
            $this->controller->setResponseSegment('calendar','index','index','show-calendar'); 
            $this->controller->setResponseSegment('friends','index','index','show-friends'); 
            $this->controller->setResponseSegment('forum','forum','index','show-favourite-forums'); 
            $this->controller->setResponseSegment('menu','index','index','menu'); 
            $this->controller->setResponseSegment('left-banner','banner','index','show-left-banner'); 
            $this->controller->setResponseSegment('right-banner','banner','index','show-right-banner'); 
            $this->controller->setResponseSegment('market','market','index','show-my-player-offers'); 
        }
        elseif($this->layoutName == 'page'){
            $this->controller->setResponseSegment('calendar','index','index','show-calendar'); 
            $this->controller->setResponseSegment('friends','index','index','show-friends'); 
            $this->controller->setResponseSegment('menu','index','index','menu'); 
            $this->controller->setResponseSegment('left-banner','banner','index','show-left-banner'); 
        }
        elseif($this->layoutName == 'fullpage'){
            $this->controller->setResponseSegment('friends','index','index','show-friends'); 
            $this->controller->setResponseSegment('menu','index','index','menu'); 
        }
        elseif($this->layoutName == 'nolog'){
//            $this->controller->setResponseSegment('calendar','index','index','show-calendar'); 
//            $this->controller->setResponseSegment('friends','index','index','show-friends'); 
//            $this->controller->setResponseSegment('menu','index','index','menu'); 
            $this->controller->setResponseSegment('left-banner','banner','index','show-left-banner'); 
        }
    }
    
    
}