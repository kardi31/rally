<?php

require_once(BASE_PATH."/library/TK/Form/Element.php");
class Radio extends Element {
    //put your code here
    
    protected $multiOptions = null;
    protected $elementDisplay;
    protected $value;
    protected $defaultLayout = true;
    
    public function __construct($type, $name, $options, $label = false) {
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
        else{
            if($value == $this->value)
                return "checked";
        }
        
    }
    
    public function renderElement($submitElem = 'submit'){
        $this->elementDisplay .= '<span class="radioWrapper">';
        if(count($this->multiOptions)>0){
            foreach($this->multiOptions as $option){
                $this->elementDisplay .= "<span class='radioElemWrapper'>";
                $this->elementDisplay .= "<label for=".$this->name.'_'.$option['value'].">".$option['label']."</label>";
                $this->elementDisplay .= "<input ".$this->isSelected($option['value'],$submitElem)." value='".$option['value']."' ".$this->renderParams()." name='".$this->name."' id='".$this->name.'_'.$option['value']."'  type='".$this->type."' />";
                $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
                $this->elementDisplay .= "</span>";
            }
        }
        $this->elementDisplay .= '</span>';
        return $this->elementDisplay;
    }
    
    public function renderElementFromMulti($img = false,$submitElem = 'submit'){
        $this->elementDisplay .= '<span class="radioWrapper">';
        if(count($this->multiOptions)>0){
            foreach($this->multiOptions[$this->name] as $option => $label){
                $this->elementDisplay .= "<div class='radio'>";
                $this->elementDisplay .= "<label for=".$this->name.'_'.$option.">";
                $this->elementDisplay .= "<input ".$this->isSelected($option,$submitElem)." value='".$option."' ".$this->renderParams()." name='".$this->name."' id='".$this->name.'_'.$option."'  type='".$this->type."' />";
                $this->elementDisplay .= $label;
                if($img){
                    $this->elementDisplay .= $img;
                }
                $this->elementDisplay .= "</label>";
                $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
                $this->elementDisplay .= "</div>";
            }
        }
        $this->elementDisplay .= '</span>';
        return $this->elementDisplay;
    }
    
    function renderAdminElement($nostyle=false,$submitElem = 'submit') {
        if(!$this->label){
            $this->label = $this->name;
        }
        
        if(!array_key_exists('validators',$this->options))
            $this->options['validators'] = array();
        
        foreach($this->multiOptions as $option):
            $this->elementDisplay .= "<label class='radio-inline'>
                                    <input ".$this->isSelected($option['value'],$submitElem)." value='".$option['value']."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."_".$option['value']."'  type='radio' />
                                       ".$option['label']."
                                    </label>
                    ";
        endforeach;
        
	
        if(!$nostyle){
                $this->elementDisplay = '<div class="form-group">'
                        . '<label>'.$this->label.'</label>'
                        . '<div class="radio-list">'
                        . ''.$this->elementDisplay.''
                        . '</div>'
                        . '</div>';
        }
        
	return $this->elementDisplay;
    }
    
    public function setValue($value){
        $this->value = $value;
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
    
    public function validateElement(){
        if(!parent::isSubmit())
            return "";
        
        $var = $_POST[parent::getName()];
        $response = Validator::validateSelect($var,array_values($this->multiOptions[parent::getName()]));
        if($response['result']){
            parent::validateElement();
        }
        else{
            if(is_array($response)&&array_key_exists('result',$response)&&$response['result']==false):
                $this->valid = false;
                $view = View::getInstance();
                return $view->showShortError($view->translate($response['errorMessage']));
            endif;
        }
    }
}
