<?php

require_once(BASE_PATH."/library/TK/Form/Radio.php");
require_once(BASE_PATH."/library/TK/Form/Select.php");
require_once(BASE_PATH."/library/TK/Form/Checkbox.php");
require_once(BASE_PATH."/library/TK/Form/Recaptcha.php");

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
	 protected $value;
	 protected $type;
	 protected $options;
	 protected $classes;
	 protected $params;
	 protected $no_populate;
	 protected $validators;
	 protected $filters = array();


    public function __construct($type,$name,$options,$label = false){
	$this->type = $type;
	$this->name = $name;
	$this->label = $label;
	$this->params = array();
	$this->options = $options;
	$this->validators = isset($options['validators'])?$options['validators']:null;
	$this->classes = array('label' => array(),'element' => array(),'wrapper' => array(),'elementWrapper' => array(),'overElementWrapper' => array());
    } 
        
    function renderElement($submitElem = 'submit') {
        if(!$this->label){
            $this->label = $this->name;
        }
        
        if(!array_key_exists('validators',$this->options))
            $this->options['validators'] = array();
        
        
        if($this->type=="submit"):
            $this->elementDisplay .= "<div class='formSubmitWrapper'>";
            $this->elementDisplay .= "<input value='".$this->label."' name='".$this->name."' ".$this->renderParams()." id='".$this->name."' type='".$this->type."' class='".$this->renderClasses()."' />";
            $this->elementDisplay .= "</div>";
        elseif($this->type=="checkbox"):
            $this->elementDisplay .= "<input value='".$this->getMethodVariable($this->name)."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."' />";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        elseif($this->type=="checkbox"):
            $this->elementDisplay .= "<input value='".$this->getMethodVariable($this->name)."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."' />";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        elseif($this->type=="captcha"):
	    $this->elementDisplay .= "<div class='formElemWrapper'><label for='captcha' id='captcha'>Przepisz kod z obrazka</label>";
	    $this->elementDisplay .= "<img src='/captcha' />";
	    $this->elementDisplay .= "<input name='captcha' id='captcha' type='text' />";
	    $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div></div>";
	elseif($this->type=="textarea"):
            $this->elementDisplay .= "<textarea ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."'>".$this->getMethodVariable($this->name)."</textarea>";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        else:
            $this->elementDisplay .= "<input value='".$this->prepareFieldValue($this->name)."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."' />";
            $this->elementDisplay .= $this->validateElement();
        endif;
	
	return $this->elementDisplay;
    }
    
    function renderAdminElement($nostyle=false,$submitElem = 'submit') {
        if(!$this->label){
            $this->label = $this->name;
        }
        
        if(!array_key_exists('validators',$this->options))
            $this->options['validators'] = array();
        
        
        if($this->type=="submit"):
            $this->elementDisplay .= "<div class='formSubmitWrapper'>";
            $this->elementDisplay .= '<input value="'.$this->label.'" name="'.$this->name.'" id="'.$this->name.'" type="'.$this->type.'" />';
            $this->elementDisplay .= "</div>";
	elseif($this->type=="select"):
            $this->elementDisplay .= "<select ".$this->renderParams()."  class='".$this->renderClasses()."'  name='".$this->name."' id='".$this->name."'>";
	    if(count($this->multiOptions)>0&&key_exists($this->name, $this->multiOptions)):
		foreach($this->multiOptions[$this->name] as $key => $value):
		    $this->elementDisplay .= "<option ".( $this->getMethodVariable($this->name)==$key ? ("selected") : '')." "
		    . "".( (key_exists('selected', $this->options)&&$this->options['selected']==$key) ? ("selected") : '')." "
		    . "value='".$key."' id='option_".$key."' >".$value."</option>";
		endforeach;
	    endif; 
	    $this->elementDisplay .= "</select>";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        elseif($this->type=="checkbox"):
            $this->elementDisplay .= "<input value='".$this->getMethodVariable($this->name)."1' ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."' />";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        elseif($this->type=="textarea"):
            $this->elementDisplay .= "<textarea ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."'>".$this->getMethodVariable($this->name)."</textarea>";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        elseif($this->type=="captcha"):
	    $this->elementDisplay .= "<div class='formElemWrapper'><label for='captcha' id='captcha'>Przepisz kod z obrazka</label>";
	    $this->elementDisplay .= "<img src='/captcha' />";
	    $this->elementDisplay .= "<input name='captcha' id='captcha' type='text' />";
	    $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div></div>";
        elseif($this->type=="hidden"):
	    $this->elementDisplay .= "<input value='".$this->getMethodVariable($this->name)."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."' />";
        else:
            $this->elementDisplay .= "<input value='".$this->getMethodVariable($this->name)."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()." form-control' type='".$this->type."' />";
            $this->elementDisplay .= $this->validateElement();
        endif;
	
        if(!$nostyle&&$this->type!="hidden"){
            if($this->type!="textarea"):
                $this->elementDisplay = '<div class="form-group">'
                        . '<label class="">'.$this->label
                        . '</label>'
                        . '<div class="">'
                        . ''.$this->elementDisplay.''
                        . '</div>'
                        . '</div>';
            else:
                $this->elementDisplay = '<div class="form-group">'
                        . '<label class="col-md-3 control-label">'.$this->label
                        . '</label>'
                        . '<div class="col-md-8">'
                        . ''.$this->elementDisplay.''
                        . '</div>'
                        . '</div>';
            endif;
        }
        
	return $this->elementDisplay;
    }
    
    public function getLabel(){
        return $this->label;
    }
    
    public function setLabel($value){
        $this->label = $value;
    }
    
    public function setMethod($method){
        $this->method = $method;
    }
    
    
        
    public function validateElement(){
        if(!$this->isSubmit())
            return "";
        
        if(!isset($this->validators)||empty($this->validators))
            return "";
        
        
        // if the element not exists
        // check if has not empty validator
        // if so,that would mean the validation failed  
        // previously it crashed on checking if value existed
        if(!empty($this->validators)&&in_array('notEmpty',$this->validators)){
                if(!isset($_POST[$this->name])){
                    $var = '';
                }
                else{
                    $var = $_POST[$this->name];
                }
                $response = $this->callValidator('notEmpty',$var);
                if(is_array($response)&&array_key_exists('result',$response)&&$response['result']==false):
                    $this->valid = false;
                    $view = View::getInstance();
                    return $view->showShortError($view->translate($response['errorMessage']));
                endif;
        }
        
        if($this->method=="POST"):{
            if(!isset($_POST[$this->name]))
                return false;
            $var = $_POST[$this->name];
        }
        elseif($this->method=="GET"):
            if(!isset($_GET[$this->name]))
                return false;
            $var = $_GET[$this->name];
        endif;
        
        $var = $this->filterVar($var);
        
        $response = "";
        // dla kilku validatorow
        if(is_array($this->validators)):
            foreach($this->validators as $key => $validator):
            $options = null;
            // gdy validator ma jakies opcje to klucz jest nazwa validatora a wartosc to opcje
            if(is_array($validator)):
                $options = $validator;
                $validator = $key;
            endif;
                // wywolywanie odpowiedniego validatora
                $response = $this->callValidator($validator,$var,$options);
                
                // jeżeli już został wykryty błąd kończymy pętle
                if(is_array($response)&&array_key_exists('result',$response)&&!$response['result']){
                        break;
                }
            endforeach; 
        else:
            // dla pojedynczego validatora
                $response = $this->callValidator($this->validators,$var);
        endif; 
        
        // zwracamy wartość formularza
        if(is_array($response)&&array_key_exists('result',$response)&&$response['result']==false):
            $this->valid = false;
            $view = View::getInstance();
            return $view->showShortError($view->translate($response['errorMessage']));
        endif;
    }
    
    public function getValid(){
        $this->validateElement();
	return $this->valid;
    }
    
    public function getMethodVariable($variableName){
        if($this->no_populate)
            return '';
        
        if(!$this->isSubmit()){
            if(isset($this->value)&&strlen($this->value)){
                return $this->value;
            }
            return "";
        }
        if($this->method=="POST"):
            if(!isset($_POST[$variableName]))
                return '';
            return $_POST[$variableName];
        elseif($this->method=="GET"):
            if(!isset($_GET[$variableName]))
                return '';
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
        $var = strip_tags($var);
        switch($validator):
            case "int":
                $response = Validator::validateInt($var);
                break;
            case "string":
                $response = Validator::validateString($var);
                break;
            case "alnum":
                $response = Validator::validateAlnum($var);
                break;
            case "alphanum":
                $response = Validator::validateAlphanum($var);
                break;
            case "notEmpty":
                $response = Validator::validateNotEmpty($var);
                break;
            case "letterLength":
                $response = Validator::validateLetterLength($var,$options);
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
    
    public function renderClasses($element = 'element'){
        if(empty($this->classes))
            return "";
	$classes = $this->classes[$element];
	$classList = implode(' ',$classes);
	
	return $classList;
    }
    
    public function addAdminDefaultClasses(){
	$this->classes['wrapper'] = array('form-group');
	$this->classes['label'] = array('control-label','col-md-3');
	$this->classes['overElementWrapper'] = array('col-md-4');
	$this->classes['element'] = array('form-control');
    }
    
    public function addClass($class,$type='element'){
	$this->classes[$type][] = $class;
    }
    
    public function renderParams(){
	if(empty($this->params)&&$this->type!="number")
	    return '';
	$result = "";
	foreach($this->params as $attribute => $value):
	    $result .= $attribute."='".$value."' ";
	endforeach;
        if($this->type=="number"){
             $result .= " min='0'";
        }
        
	return $result;
    }
    
    public function addParam($attribute,$value = false){
        if(!$value)
            $value = $attribute;
	$this->params[$attribute] = $value;
    }
    
    public function addValidator($name,$options = false){
        if(is_array($this->validators)){
            if(!$options)
                array_push($this->validators,$name);
            else
                $this->validators[$name] = $options;
        }
        else{
            if(!$options){
                $this->validators = array($name);
            }
            else
                $this->validators[$name] = $options;
        }
        
    }
    
    public function setValue($value){
        $this->value = $value;
    }
    
    public function prepareFieldValue(){
//        var_dump($this->getMethodVariable($this->name));exit;
        if(strlen($this->getMethodVariable($this->name))){
            return $this->getMethodVariable($this->name);
        }
        elseif(isset($this->value)){
            return $this->value;
        }
        else{
            return '';
        }
    }
    
    public function filterVar($var){
        if(empty($this->filters)){
            return $var;
        }
        
        foreach($this->filters as $filter){
            switch($filter):
                case 'trim':
                    $_POST[$this->name] = trim($_POST[$this->name]);
                    $var = trim($var);
                    break;
                case 'lower':
                    $_POST[$this->name] = strtolower($_POST[$this->name]);
                    $var = strtolower($var);
                    break;
                case 'specialchars':
                    $_POST[$this->name] = TK_Text::filterSpecialChars($_POST[$this->name]);
                    $var = TK_Text::filterSpecialChars($var);
            endswitch;
        }
        
        return $var;
    }
    
    public function addFilter($name){
        array_push($this->filters,$name);
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function setNoPopulate(){
        $this->no_populate = true;
    }
    
    public function getName(){
        return $this->name;
    }
    
}