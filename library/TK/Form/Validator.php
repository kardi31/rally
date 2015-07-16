<?php


class Validator{
         
    public function __construct(){
    }
    
    
    
    public static function validateInt($var) {
        if(is_int($var)||is_numeric($var)):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Podana wartość nie jest liczbą";
        endif;
        
        return $response;
    }
    
    public static function validateString($var) {
        if(is_string($var)):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Podana wartość może zawierać tylko litery";
        endif;
        
        return $response;
    }
    
    public static function notEmpty($var) {
        if(strlen($var)>0):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Pole nie może być puste";
        endif;
        
        return $response;
    }
    
    public static function validateAlnum($var){
	$pattern = '/^[A-Za-zążśźęćńółĄŻŚŹĘĆŃÓŁ0-9\s]+$/';
	if(preg_match($pattern,$var)==1):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Podana wartość może zawierać tylko litery, cyfry i spacje";
        endif;
        
        return $response;
    }
    
    public static function validateStringLength($var,$options) {
        if(array_key_exists('min',$options)&&strlen($var)<$options['min']):
            $response['result'] = false;
            $response['errorMessage'] = "Pole za krótkie, minimalnie ".$options['min']." znak(i/ów)";
        elseif(array_key_exists('max',$options)&&strlen($var)>$options['max']):
            $response['result'] = false;
            $response['errorMessage'] = "Pole za długie, maksymalnie ".$options['max']." znak(i/ów)";
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
            $response['errorMessage'] = "Formularz błędnie napisany. Skontaktuj się z administracją";
            return $response;
        endif;
        
        if($var == $checkVar):
            $response['result'] = true;
        else:
            $response['result'] = false;
            if($options['elem']=="password")
                $response['errorMessage'] = "Hasła nie pasują do siebie";
            else
                $response['errorMessage'] = "Elementy nie pasują do siebie";
        endif;
        
        return $response;
    }
    
    public static function validateEmail($var) {        
        if(!filter_var($var,FILTER_VALIDATE_EMAIL)):
            $response['result'] = false;
            $response['errorMessage'] = "Adres email nie jest poprawny";
            
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
            $response['errorMessage'] = "Formularz błędnie napisany. Skontaktuj się z administracją";
            return $response;
        endif;
        if($_SESSION['originalkey'] == $checkVar&&strlen($checkVar)):
            $response['result'] = true;
        else:
            $response['result'] = false;
            $response['errorMessage'] = "Niepoprawny tekst";
            
        endif;
	
        return $response;
    }
    
    
}