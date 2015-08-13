<?php
 
class TK_Helper{
         
    public function __construct(){
       
    }
    
    public static function redirect($url) {
        header('Location: '.$url);
       /* ?>
        <script type="text/javascript">
        document.location.href="<?php echo $url; ?>";
        </script>
        <?php */
    }
    
    public static function displayPeopleSkillsOnList($person,$skill,$showEmpty = true){
        $result = "";
        $result .= str_repeat("<img src='/images/gwiazdka.png' alt='gw' />", (int)$person[$skill]);
        if($showEmpty)
        $result .= str_repeat("<img src='/images/gwiazdka-empty.png' class='gwEmpty' alt='gw' />", 10-(int)$person[$skill]);
        
        if($showEmpty&&$skill!="form"&&$skill!="talent"){
            if($person['active_training_skill']==$skill){
                $result .= '<a class="trainingChange" title="This skill is currently trained by your player" href="#">In progress</a>';
            }
            else{
                $result .= '<a class="trainingChange" title="Set this skill to be trained by your player" href="/people/set-active-training-skill/id/'.$person['id'].'/skill/'.$skill.'/">Improve</a>';
            }
        }
        return $result;
    }
    
    public static function showCarParameters($car){
        $view = View::getInstance();
        $html = '
        <div class="carParameters">
                        <table>
                           <tr>
                               <td>'.$view->translate('brand').':</td>
                               <td>'.$car['name'].'</td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('capacity').'</td>
                               <td>'.$car['capacity'].'</td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('horse power').'</td>
                               <td>'.$car['horsepower'].'</td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('v-max').'</td>
                               <td>'.$car['max_speed'].'</td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('acceleration').'</td>
                               <td>'.$car['acceleration'].'</td>
                           </tr>

                       </table>
                   </div>';
        return $html;
    }
    
    public static function showPersonDetails($person,$showTraining = false){
        $view = View::getInstance();
        if($person['job'] == "driver"): 
             $html .= '
                    <table>
                        <tr class="'.(($person['active_training_skill']=='composure'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Composure').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'composure',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='speed'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Speed').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'speed',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='regularity'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Regularity').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'regularity',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='reflex'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Reflex').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'reflex',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='on_gravel'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Driving on gravel').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'on_gravel',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='on_tarmac'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Driving on tarmac').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'on_tarmac',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='on_snow'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Driving on snow').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'on_snow',$showTraining).'
                            </td>
                        </tr>
                        <tr class="'.(($person['active_training_skill']=='in_rain'&&$showTraining)?'active':'').'">
                            <td>'.$view->translate('Driving in rain').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'in_rain',$showTraining).'
                            </td>
                        </tr>
                        <tr>
                            <td>Talent</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'talent',$showTraining).'
                            </td>
                        </tr>
                        <tr>
                            <td>'.$view->translate('Form').'</td>
                            <td>
                                '.TK_Helper::displayPeopleSkillsOnList($person, 'form',$showTraining).'
                            </td>
                        </tr>
                    </table>';
        else: 
            $html .= '
        <table>
            <tr class="'.(($person['active_training_skill']=='composure'&&$showTraining)?'active':'').'">
                <td>'.$view->translate('Composure').'</td>
                <td>
                    '.TK_Helper::displayPeopleSkillsOnList($person, 'composure',$showTraining).'
                </td>
            </tr>
            <tr class="'.(($person['active_training_skill']=='dictate_rhytm'&&$showTraining)?'active':'').'">
                <td>'.$view->translate('Dictate rhytm').'</td>
                <td>
                    '.TK_Helper::displayPeopleSkillsOnList($person, 'dictate_rhytm',$showTraining).'
                </td>
            </tr>
            <tr class="'.(($person['active_training_skill']=='diction'&&$showTraining)?'active':'').'">
                <td>'.$view->translate('Diction').'</td>
                <td>
                    '.TK_Helper::displayPeopleSkillsOnList($person, 'diction',$showTraining).'
                </td>
            </tr>
            <tr class="'.(($person['active_training_skill']=='route_description'&&$showTraining)?'active':'').'">
                <td>'.$view->translate('Route description').'</td>
                <td>
                    '.TK_Helper::displayPeopleSkillsOnList($person, 'route_description',$showTraining).'
                </td>
            </tr>

            <tr>
                <td>Talent</td>
                <td>
                    '.TK_Helper::displayPeopleSkillsOnList($person, 'talent',$showTraining).'
                </td>
            </tr>
            <tr>
                <td>'.$view->translate('Form').'</td>
                <td>
                    '.TK_Helper::displayPeopleSkillsOnList($person, 'form',$showTraining).'
                </td>
            </tr>
        </table>';
        endif; 
        return $html;
    }
    
}
  
?>
