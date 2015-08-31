<?php

class Recaptcha{
    //put your code here
    
    protected $elementDisplay;
    protected $value;
    protected $method  = "POST";
         protected $submitElem = "submit";
    
    public function __construct($type, $name, $options, $label = false) {
       $this->name = $name;
    }
    
    
    
    public function renderElement($submitElem = 'submit'){
        $this->elementDisplay = "<script src='https://www.google.com/recaptcha/api.js'></script>
        <div class='g-recaptcha' data-sitekey='6LfwGAwTAAAAAD6T987vkxXiNcQZCeS4v9RxkJbA'></div>";
        $this->elementDisplay .= "<div class='formError'>".$this->validateElement()."</div>";
        $this->elementDisplay .= '</span>';
        return $this->elementDisplay;
    }
    
    public function validateElement(){
        if(!$this->isSubmit())
            return "";
        
        
//        die('31');
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret'   => '6LfwGAwTAAAAAKnQpLN5bYig2xyaNJE2VNbuJk18',
                 'response' => $_POST['g-recaptcha-response'],
                 'remoteip' => $_SERVER['REMOTE_ADDR']];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data) 
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result,true);
        
        // zwracamy wartość formularza
        if(!$result['success']):
            $this->valid = false;
            $view = View::getInstance();
            
            switch($result['error-codes'][0]){
                case 'missing-input-response':
                    $msg = 'Fill in the captcha field';
                    break;
                default:
                    $msg = $result['error-codes'][0];
                    break;
            }
            
            return $view->showShortError($view->translate($msg));
        endif;
        
        $this->valid = true;
        return true;
    }
    
    
    public function isSubmit(){
        if($this->method=="POST"&&isset($_POST[$this->submitElem])):
                return true;
        elseif($this->method=="GET"&&isset($_GET[$this->submitElem])):
               return true;
        endif;
        return false;
    }
    
    
    public function getValid(){
        $this->validateElement();
	return $this->valid;
    }
}
