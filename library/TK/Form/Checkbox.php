<?php

require_once(BASE_PATH."/library/TK/Form/Element.php");
class Checkbox extends Element {
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
    
    public function isChecked($value,$submitElem){
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
        $this->elementDisplay = "<input value='0' name='".$this->name."' id='hidden_".$this->name."' type='hidden' />";
        
        $this->elementDisplay .= "<input value='1'".$this->isChecked($this->name,$submitElem) ." ".$this->renderParams()." name='".$this->name."' id='".$this->name."' class='".$this->renderClasses()."' type='".$this->type."' />";
        $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
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
    
}
