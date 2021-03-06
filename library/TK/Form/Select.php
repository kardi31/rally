<?php

require_once(BASE_PATH."/library/TK/Form/Element.php");
class Select extends Element {
    //put your code here
    
    protected $multiOptions = null;
    protected $elementDisplay;
    protected $defaultLayout = true;
    protected $value;
    protected $validateSelect = true;
    
    
    public function __construct($type, $name, $options, $label = false) {
        $this->value = false;
        parent::__construct($type, $name, $options, $label);
    }
    
    public function addMultiOption($value,$label = null){
        $counter = count($this->multiOptions)+1;
        
        $this->multiOptions[$counter]['value'] = $value;
        $this->multiOptions[$counter]['label'] = $label;
    }
    
    public function isSelected($value,$submitElem){
        if(isset($_POST[$submitElem])){
            if($value==$_POST[$this->name])
                return "checked";
        }
        
    }
    
    public function setNoValidateSelect(){
        $this->validateSelect = false;
    }
    
    public function renderElement($submitElem = 'submit'){
        $this->elementDisplay .= "<select ".$this->renderParams()."  class='".$this->renderClasses()."'  name='".$this->name."' id='".$this->name."'>";
	    if(count($this->multiOptions)>0&&key_exists($this->name, $this->multiOptions)):
                
		foreach($this->multiOptions[$this->name] as $key => $value):
		    $this->elementDisplay .= "<option ";
                            // disabled
//                            (strlen($value)<1?'readonly ':' ');
                            // selected by default
                            if(strlen($this->getMethodVariable($this->name))&&$this->getMethodVariable($this->name)==$key){
                                $this->elementDisplay .= "selected";
                            }
                            elseif(isset($this->value)&&strlen($this->value)&&$this->value == $key){
                                $this->elementDisplay .= "selected";
                            }
                   $this->elementDisplay .= " ". 
                            "value='".$key."' "
                            . "id='option_".$key."' >".
                                $value.
                            "</option>";
		endforeach;
	    endif; 
	    $this->elementDisplay .= "</select>";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
            
            
        return $this->elementDisplay;
    }
    
    public function renderAdminElement($nostyle=false,$submitElem = 'submit'){
        $this->elementDisplay .= "<select ".$this->renderParams()."  class='".$this->renderClasses()." form-control'  name='".$this->name."' id='".$this->name."'>";
	    if(count($this->multiOptions)>0&&key_exists($this->name, $this->multiOptions)):
                
		foreach($this->multiOptions[$this->name] as $key => $value):
		    $this->elementDisplay .= "<option ";
                            // disabled
//                            (strlen($value)<1?'readonly ':' ');
                            // selected by default
                            if(strlen($this->getMethodVariable($this->name))&&$this->getMethodVariable($this->name)==$key){
                                $this->elementDisplay .= "selected";
                            }
                            elseif(isset($this->value)&&strlen($this->value)&&$this->value == $key){
                                $this->elementDisplay .= "selected";
                            }
                   $this->elementDisplay .= " ". 
                            "value='".$key."' "
                            . "id='option_".$key."' >".
                                $value.
                            "</option>";
		endforeach;
	    endif; 
	    $this->elementDisplay .= "</select>";
            $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
            
            if(!$nostyle){
                $this->elementDisplay = '<div class="form-group">'
                        . '<label class="">'.$this->label
                        . '</label>'
                        . '<div class="">'
                        . ''.$this->elementDisplay.''
                        . '</div>'
                        . '</div>';
            }
            
        return $this->elementDisplay;
    }
    
    public function addMultiOptions($options,$emptyOption = false){
	
	if($emptyOption){
            if(!is_bool($emptyOption))
                $this->multiOptions[$this->name][''] = $emptyOption;
            else
                $this->multiOptions[$this->name][''] = '';
        }
        
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
    
    public function setValue($value){
        $this->value = $value;
    }
    
    public function validateElement(){
        if(!parent::isSubmit()||!isset($_POST[parent::getName()]))
            return "";
        
        $var = $_POST[parent::getName()];
        if($this->validateSelect){
            $response = Validator::validateSelect($var,array_keys($this->multiOptions[parent::getName()]));
        }
        
        if(isset($response)&&$response['result']){
            parent::validateElement();
        }
        else{
            if(isset($response)&&is_array($response)&&array_key_exists('result',$response)&&$response['result']==false):
                $this->valid = false;
                $view = View::getInstance();
                return $view->showShortError($view->translate($response['errorMessage']));
            endif;
        }
    }
}

