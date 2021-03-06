<?php
 
class View{
 
    protected $variables;
    public $controller_instance;
    protected $_settings;
    public $render = 1;
    private static $instance = NULL;
    private static $doc;
    private $headTitle;
    private $metaDescription;
    private $metaKeywords;
    
    public function __construct() {
	
        if(!isset(self::$doc)&&get_class(self::$doc)!="DOMDocument"){
            self::$doc = new DomDocument();
            self::$doc->Load(BASE_PATH."/config/translate.xml");
        }
    }
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new View();
       return self::$instance;
    }
    
    public function getSetting($param){
        if(!empty($this->_settings))
            return $this->_settings[$param];
        $ini_array = parse_ini_file(BASE_PATH."/config/config.ini");
        
        if($ini_array)
            $this->_settings = $ini_array;
        
        return $this->_settings[$param];
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
 
    public function showShortError($message){
        ob_start();
        
        include(BASE_PATH."/views/message/short-error.phtml");
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    public function showError($message){
        include(BASE_PATH."/views/message/error.phtml");
    }
    
    public function showSuccess($message){
        include(BASE_PATH."/views/message/success.phtml");
    }
    
    public function translate($string,$trItem = 0){
        if(isset($_COOKIE['lang'])||isset($GLOBALS['urlParams']['lang'])){
            if(isset($GLOBALS['urlParams']['lang'])){
                $lang = $GLOBALS['urlParams']['lang'];
            }
            else{
                $lang = $_COOKIE['lang'];
            }
            try{
                $elem = self::$doc->getElementById($string);
                if($elem){
                    $nodeElem = $elem->getElementsByTagName($lang)->item($trItem);
                    if(is_object($nodeElem)&&strlen($nodeElem->nodeValue)){
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
    
    public function headTitle(){
        return $this->headTitle;
    }
    
    public function setHeadTitle($title){
        $this->headTitle = $title;
    }
    
}