<?php
 
class TK_Helper{
         
    public function __construct(){
       
    }
    
    public static function redirect($url) {
        ?>
        <script type="text/javascript">
        document.location.href="<?php echo $url; ?>";
        </script>
        <?php
    }
    
    public static function displayPeopleSkillsOnList($person,$skill,$showEmpty = true){
        echo str_repeat("<img src='/images/gwiazdka.png' alt='gw' />", (int)$person[$skill]);
        if($showEmpty)
        echo str_repeat("<img src='/images/gwiazdka-empty.png' class='gwEmpty' alt='gw' />", 10-(int)$person[$skill]);
        
        if($showEmpty&&$skill!="form"&&$skill!="talent"){
            if($person['active_training_skill']==$skill){
                echo '<a class="trainingChange" title="This skill is currently trained by your player" href="#"><i class="fa fa-circle-o"></i></a>';
            }
            else{
                echo '<a class="trainingChange" title="Set this skill to be trained by your player" href="/people/set-active-training-skill/id/'.$person['id'].'/skill/'.$skill.'/"><i class="fa fa-circle-o fa-circle-empty"></i></a>';
            }
        }
    }
    
}
  
?>
