<?php
 
class View{
 
    protected $variables;
    public $controller_instance;
    public $render = 1;
    private static $instance = NULL;
    private static $doc;
    
    public function __construct() {
	
        if(!isset(self::$doc)&&get_class(self::$doc)!="DOMDocument"){
            self::$doc = new DomDocument();
//            self::$doc->validateOnParse = false;
            self::$doc->Load(BASE_PATH."/config/translate.xml");
        }
    }
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new View();
       return self::$instance;
    }
    
    function render($module,$viewName,$zone){
	if(count($this->variables)>0):
            foreach($this->variables as $name => $val):
                ${$name} = $val;
            endforeach;
        endif; 
        $module = strtolower($module);
	ob_start(); // start output buffer

	include BASE_PATH . '/modules/'.$module.'/views/'.$zone.'/' . $viewName .'.phtml';
	$template = ob_get_contents(); // get contents of buffer
	ob_end_clean();
	return $template;
    }
    
    function showFile($file){
	if(count($this->variables)>0):
            foreach($this->variables as $name => $val):
                ${$name} = $val;
            endforeach;
        endif; 
	ob_start(); // start output buffer

	include BASE_PATH . '/views/'.$file;
	$template = ob_get_contents(); // get contents of buffer
	ob_end_clean();
	return $template;
    }
    
    public function assign($name,$value){
        $this->variables[$name] = $value;
    }
    
    public function getControllerInstance(){
        return $this->controller_instance;
    }
    
    public function setNoRender(){
        $this->render = 0;
    }
    
    public function requireDTFactory(){
        require_once(BASE_PATH.'/modules/index/library/DataTables/Factory.php');
    }
 
    public function showError($message){
        include(BASE_PATH."/views/message/error.phtml");
    }
    
    public function showSuccess($message){
        include(BASE_PATH."/views/message/success.phtml");
    }
    
    public function translate($string){
        if(isset($_COOKIE['lang'])){
            $lang = $_COOKIE['lang'];
            try{
                $elem = self::$doc->getElementById($string);
                if($elem){
                    $nodeElem = $elem->getElementsByTagName($lang)->item(0);
                    if(strlen($nodeElem->nodeValue)){
                        return $nodeElem->nodeValue;
                    }
                    else{
                        return $string;
                    }
                }
                else
                    return $string;
            }
            catch(Exception $e){
                return $string;
            }
        }
        else{
            return $string;
        }
    }
    
    
    
}