<?php

class Element{
         protected $elementDisplay;
         protected $method  = "POST";
         protected $action;
         protected $error = false;
         protected $valid = true;
         protected $multiOptions;
         protected $submitElem = "submit";
	 
	 protected $label;
	 protected $name;
	 protected $type;
	 protected $options;


    public function __construct($type,$name,$options,$label = false){
	$this->type = $type;
	$this->name = $name;
	$this->label = $label;
	$this->options = $options;
    } 
        
    function renderElement($submitElem) {
        if(!$this->label){
            $this->label = $this->name;
        }
        
        if(!array_key_exists('validators',$this->options))
            $this->options['validators'] = array();
        
        
        if($this->type=="submit"):
            $this->elementDisplay .= "<div class='formSubmitWrapper'>";
            $this->elementDisplay .= "<input value=".$this->label." name='".$this->name."' id='".$this->name."' type='".$this->type."' />";
            $this->elementDisplay .= "</div>";
	elseif($this->type=="select"):
            $this->elementDisplay .= "<div class='formElemWrapper'><label for='".$this->name."' id='".$this->name."Label' type='".$this->type."' >".$this->label."</label>";
	    $this->elementDisplay .= "<select name='".$this->name."' id='".$this->name."'>";
	    if(key_exists($this->name, $this->multiOptions)):
		foreach($this->multiOptions[$this->name] as $key => $value):
		    $this->elementDisplay .= "<option ".( $this->getMethodVariable($this->name)==$key ? ("selected") : '')." "
		    . "".( (key_exists('selected', $this->options)&&$this->options['selected']==$key) ? ("selected") : '')." "
		    . "value='".$key."' id='option_".$key."' >".$value."</option>";
		endforeach;
	    endif; 
	    $this->elementDisplay .= "</select>";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement($this->name,$this->options['validators'])."</div></div>";
        else:
            $this->elementDisplay .= "<div class='formElemWrapper'><label for='".$this->name."' id='".$this->name."Label' type='".$this->type."' >".$this->label."</label>";
            $this->elementDisplay .= "<input value='".$this->getMethodVariable($this->name)."' name='".$this->name."' id='".$this->name."' type='".$this->type."' />";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement($this->name,$this->options['validators'])."</div></div>";
        endif;
	
	return $this->elementDisplay;
    }
    
    public function setMethod($method){
        $this->method = $method;
    }
    
    public function addMultiOptions($options){
	
	if(count($options)==0)
	    return ;
	
	// set pointer to begining of array
	reset($options);
	$first_value = current($options);
	
	// if as a value we get an array then first col is a key and second is a value
	if(is_array($first_value)):
	    
	    $keys = array_keys($first_value);
	    foreach($options as $key => $value):
		$this->multiOptions[$this->name][$value[$keys[0]]] = $value[$keys[1]];
	    endforeach;
	else:
	    foreach($options as $key => $value):
		$this->multiOptions[$this->name][$key] = $value;
	    endforeach;
	endif;    
	
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
            return $response['errorMessage'];
        endif;
        
        
    }
    
    public function getValid(){
	return $this->valid;
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
        $prefix = "<form class='TK_Form' method='".$this->method."' action='".$this->action."' enctype='multipart/form-data'>";
        if($this->error!==false)
            $prefix .= "<div class='formError'>".$this->error."</div>";
        $suffix = "</form>";
        
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
    
    public function createCaptcha(){
       
        $this->form .= "<div class='formElemWrapper'><label for='captcha' id='captcha'>Przepisz kod z obrazka</label>";
        $this->form .= "<img src='/captcha' />";
        $this->form .= "<input name='captcha' id='captcha' type='text' />";
        $this->form .= "<div class='formError'>".$this->validateElement('captcha','captcha')."</div></div>";
    }
    
    public function isValid(){
        return $this->valid;
    }
    
    public function setError($message){
        $this->error = $message;
    }
}