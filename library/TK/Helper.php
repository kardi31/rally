<?php
 
class TK_Helper{
         
    public function __construct(){
       
    }
    
    public static function redirect($url) {
        header('Location: '.$url);
    }
    
    
}
  
?>
