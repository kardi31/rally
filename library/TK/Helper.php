<?php
 
class TK_Helper{
         
    public function __construct(){
       
    }
    
    public static function redirect($url) {
        header('Location: '.$url);
    }
    
    public static function displayPeopleSkillsOnList($person,$skill,$showEmpty = true){
        echo str_repeat("<img src='/images/gwiazdka.png' alt='gw' />", (int)$person[$skill]);
        if($showEmpty)
        echo str_repeat("<img src='/images/gwiazdka-empty.png' class='gwEmpty' alt='gw' />", 10-(int)$person[$skill]);
        
        if($person['active_training_skill']==$skill){
            echo "+++";
        }
    }
    
}
  
?>
