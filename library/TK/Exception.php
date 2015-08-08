<?php
 
class TK_Exception extends Exception{
         
    
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code
    
        if($code == 404){
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            TK_Helper::redirect('/error404?msg='.urlencode($message));
        }
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "A custom function for this type of exception\n";
    }
}