<?php

require_once(BASE_PATH."/library/TK/Form/Element.php");
class Select extends Element {
    //put your code here
    
    protected $multiOptions = null;
    protected $elementDisplay;
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
        
    }
    
    public function renderElement($submitElem = 'submit'){
        $this->elementDisplay .= "<select ".$this->renderParams()."  class='".$this->renderClasses()."'  name='".$this->name."' id='".$this->name."'>";
	    if(count($this->multiOptions)>0&&key_exists($this->name, $this->multiOptions)):
                
		foreach($this->multiOptions[$this->name] as $key => $value):
                
		    $this->elementDisplay .= "<option ".
                            // disabled
                            (strlen($value)<1?'disabled ':' ').
                            // selected on post
                            ( $this->getMethodVariable($this->name)==$key ? ("selected") : '').
                            " ".
                            // selected by default
                            ( (key_exists('selected', $this->options)&&$this->options['selected']==$key) ? ("selected") : '').
                            " ". 
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
    
    public function addMultiOptions($options,$emptyOption = false){
	
	if(count($options)==0)
	    return ;
	
	// set pointer to begining of array
	reset($options);
	$first_value = current($options);
	if($emptyOption)
	    $this->multiOptions[$this->name][''] = '';
	// if as a value we get an array then first col is a key and second is a value
	if(is_array($first_value)):
	    $keys = array_keys($first_value);
	    foreach($options as $key => $value):
		$this->multiOptions[$this->name][$value[$keys[0]]] = $value[$keys[1]];
	    endforeach;
	else:
	    foreach($options as $key => $value):
		$this->multiOptions[$this->name][$value] = $value;
	    endforeach;
	endif;    
	
    }
}
