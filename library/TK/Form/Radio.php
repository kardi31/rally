<?php

require_once(BASE_PATH."/library/TK/Element.php");
class Radio extends Element {
    //put your code here
    
    protected $multiOptions = null;
    protected $elementDisplay;
    protected $defaultLayout = true;
    
    public function __construct($type, $name, $options, $label = false) {
        parent::__construct($type, $name, $options, $label);
    }
    
    public function addMultiOption($name,$value,$label = null){
        $this->multiOptions[$name]['value'] = $value;
        $this->multiOptions[$name]['label'] = $label;
    }
    
    public function renderElement($submitElem = 'submit'){
        if(count($this->multiOptions)>0){
            foreach($this->multiOptions as $key => $value){
                $this->elementDisplay .= "<label for=".$this->name.'_'.$value.">".$label."</label>";
                $this->elementDisplay .= "<input ".isSelected($value,$submitElem)." value='".$value."' ".$this->renderParams()." name='".$this->name."' id='".$this->name."'  type='".$this->type."' />";
                $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
            }
        }
        
        return $this->elementDisplay;
    }
    
    public function isChecked($value){
        
    }
}
