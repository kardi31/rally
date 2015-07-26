<?php


class Validator{
         
    public function __construct(){
    }
    
    
    
    public static function validateInt($var) {
        if(is_int($var)||is_numeric($var)):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "This value is not a number";
        endif;
        
        return $response;
    }
    
    public static function validateString($var) {
        if(is_string($var)):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "This field may contain only letters";
        endif;
        
        return $response;
    }
    
    public static function notEmpty($var) {
        if(strlen($var)>0):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "This field cannot be empty";
        endif;
        
        return $response;
    }
    
    public static function validateAlnum($var){
	$pattern = '/^[A-Za-zążśźęćńółĄŻŚŹĘĆŃÓŁ0-9\s]+$/';
	if(preg_match($pattern,$var)==1):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "This field may contain only letters,numbers and spaces";
        endif;
        
        return $response;
    }
    
    public static function validateStringLength($var,$options) {
        if(array_key_exists('min',$options)&&strlen($var)<$options['min']):
            $response['result'] = false;
            $response['errorMessage'] = "This text is too short. Type at least ".$options['min']." characters";
        elseif(array_key_exists('max',$options)&&strlen($var)>$options['max']):
            $response['result'] = false;
            $response['errorMessage'] = "This text is too long. Type maximum of ".$options['max']." characters";
        else:
            $response['result'] = true;
        endif;
        return $response;
    }
    
    public static function validateMatch($var,$options,$method) {
        // zmienna do której porównujemy
        if($method=="POST"):
            $checkVar = $_POST[$options['elem']];
        elseif($method=="GET"):
            $checkVar = $_GET[$options['elem']];
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Form error. Contact with administration";
            return $response;
        endif;
        
        if($var == $checkVar):
            $response['result'] = true;
        else:
            $response['result'] = false;
            if($options['elem']=="password")
                $response['errorMessage'] = "Passwords do not match";
            else
                $response['errorMessage'] = "Elements do not match";
        endif;
        
        return $response;
    }
    
    public static function validateEmail($var) {        
        if(!filter_var($var,FILTER_VALIDATE_EMAIL)):
            $response['result'] = false;
            $response['errorMessage'] = "This email address is not valid";
            
        else:
           $response['result'] = true;
        endif;
        
        return $response;
    }
    
    public static function validateCaptcha($var,$method) {

        // zmienna do której porównujemy
        if($method=="POST"):
            $checkVar = $_POST['captcha'];
        elseif($method=="GET"):
            $checkVar = $_GET['captcha'];
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Form error. Contact with administration";
            return $response;
        endif;
        if($_SESSION['originalkey'] == $checkVar&&strlen($checkVar)):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Wrong captcha";
            
        endif;
	
        return $response;
    }
    
    
}