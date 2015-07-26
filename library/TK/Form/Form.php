<?php

require(BASE_PATH.'/library/TK/Form/Validator.php');
require(BASE_PATH.'/library/TK/Form/Element.php');

class Form extends Element{
         protected $form;
         protected $method  = "POST";
         protected $action;
         protected $error = false;
         protected $valid = true;
         protected $multiOptions;
         protected $submitElem = "submit";
	 protected $elements;
	 protected $classes;
         
         
    public function __construct(){
	$this->multiOptions = array();
	$this->elements = array();
	$this->classes = array();
    }
    
    
    
    public function createForm() {
        
    }
    
    function createElement($type,$name,$options = array(),$label = null) {
        if($type == "radio"){
            $element = new Radio($type,$name,$options,$label);
        }
        elseif($type == "select"){
            $element = new Select($type,$name,$options,$label);
        }elseif($type == "checkbox"){
            $element = new Checkbox($type,$name,$options,$label);
        }
        else{
            $element = new Element($type,$name,$options,$label);
        }
	$this->elements[$name] = $element;
	
	if($type == "submit")
	    $this->submitElem = $name;
	
	return $element;
    }
    
    function getElement($name){
	if (is_array($this->elements)&&array_key_exists($name, $this->elements)) {
            return $this->elements[$name];
        }
        return null;
    }
    
    public function setMethod($method){
        $this->method = $method;
    }
    
    function populate($values){
        foreach($this->elements as $key => $element):
            if(isset($values[$key])){
                $element->setValue($values[$key]);
            }
        endforeach;
    }
    
    
    public function validateElement($name,$validators = null){
        if(!$this->isSubmit())
            return "";
        
        if($this->method=="POST"):
            $var = $_POST[$name];
        elseif($this->method=="GET"):
            $var = $_GET[$name];
        endif;
	
        $response = "";
        // dla kilku validatorow
        if(is_array($validators)):
            foreach($validators as $key => $validator):
            // gdy validator ma jakies opcje to klucz jest nazwa validatora a wartosc to opcje
            if(is_array($validator)):
                $options = $validator;
                $validator = $key;
            endif;
                // wywolywanie odpowiedniego validatora
                $response = $this->callValidator($validator,$var,$options);
                
                // jeżeli już został wykryty błąd kończymy pętle
                if(is_array($response)&&array_key_exists('result',$response))
                        break;
            endforeach; 
        else:
            // dla pojedynczego validatora
                $response = $this->callValidator($validators,$var);
        endif; 
        
        // zwracamy wartość formularza
        if(is_array($response)&&array_key_exists('result',$response)&&$response['result']==false):
            $this->valid = false;
            $view = View::getInstance();
            return $view->showError($view->translate($response['errorMessage']));
        endif;
        
        
    }
    
    public function getMethodVariable($variableName){
        if(!$this->isSubmit())
            return "";
        if($this->method=="POST"):
            return $_POST[$variableName];
        elseif($this->method=="GET"):
            return $_GET[$variableName];
        endif;
    }
    
    public function renderForm(){
        $prefix = "<form class='TK_Form' method='".$this->method."' action='".$this->action."' class='".$this->renderClasses()."' enctype='multipart/form-data'>";
        if($this->error!==false)
            $prefix .= "<div class='formError'>".$this->error."</div>";
        $suffix = "</form>";
	foreach($this->elements as $element):
	    $this->form .= $element->renderElement($this->submitElem);
	endforeach;
        $form = $prefix.$this->form.$suffix;
        return $form;
    }
    
    public function isSubmit(){
        if($this->method=="POST"&&isset($_POST[$this->submitElem])):
                return true;
        elseif($this->method=="GET"&&isset($_GET[$this->submitElem])):
               return true;
        endif;
        
        return false;
    }
    
    public function callValidator($validator,$var,$options = null){
   
        switch($validator):
            case "int":
                $response = Validator::validateInt($var);
                break;
            case "string":
                $response = Validator::validateString($var);
                break;
            case "notEmpty":
                $response = Validator::validateNotEmpty($var);
                break;
            case "stringLength":
                $response = Validator::validateStringLength($var,$options);
                break;
            case "match":
                $response = Validator::validateMatch($var,$options,$this->method);
                break;
            case "captcha":
                $response = Validator::validateCaptcha($var,$this->method);
                break;
            case "email":
                $response = Validator::validateEmail($var);
                break;
        endswitch;
        
        return $response;
    }
    
    public function renderCaptcha(){
       
	$captcha = '';
        $captcha .= "<div class='formElemWrapper'><label for='captcha' id='captcha'>Przepisz kod z obrazka</label>";
        $captcha .= "<img src='/captcha' />";
        $captcha .= "<input name='captcha' id='captcha' type='text' />";
        $captcha .= "<div class='formError'>".$this->validateElement('captcha','captcha')."</div></div>";
	
	return $captcha;
    }
    
    public function isValid(){
        foreach($this->elements as $element):
	    if(!$element->getValid()){
		return false;
            }
	endforeach;
	
	    return true;
    }
    
    public function setError($message){
        $this->error = $message;
    }
    
    public function addClass($class){
	$this->classes[] = $class;
    }
    
    public function renderClasses(){
        if(empty($this->classes))
            return "";
	$classes = array_keys($this->classes);
	$classList = implode(' ',$classes);
	
	return $classList;
    }
    
}