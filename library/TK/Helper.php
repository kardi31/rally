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
                $result .= '<a class="trainingChange" title="Set this skill to be trained by your player" href="/people/set-active-training-skill/'.$person['id'].'/'.$skill.'/">Improve</a>';
            }
        }
        return $result;
    }
    
    public static function showCarParameters($car,$team = false){
        $view = View::getInstance();
             $html = '
        <div class="carParameters">
                        <table>
                           <tr>
                               <td colspan="2">'.$view->translate('brand').': <strong>'.$car['Model']['name'].'</strong></td>
                           </tr>
                        ';
             if($team){
                 $html .= '<tr>
                              <td class="pt10" colspan="2">'.$view->translate('team').': <strong>'.$team['name'].'</strong></td>
                           </tr>';
             }
             $html .= '
                           <tr>
                               <td>'.$view->translate('capacity').': <strong>'.$car['Model']['capacity'].'</strong></td>
                               <td>'.$view->translate('Mileage').': <strong>'.$car['mileage'].'</strong></td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('horse power').': <strong>'.$car['Model']['horsepower'].'</strong></td>
                               <td>'.$view->translate('Value').': <strong>'.$car['value'].'</strong></td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('v-max').': <strong>'.$car['Model']['max_speed'].'</strong></td>
                               <td>'.$view->translate('Upkeep').': <strong>'.$car['upkeep'].'</strong></td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('acceleration').': <strong>'.$car['Model']['acceleration'].'</strong></td>
                               <td></td>
                           </tr>

                       </table>
                   </div>';
       
        return $html;
    }
    
    public static function showCarModelParameters($model,$team = false){
        $view = View::getInstance();
             $html = '
        <div class="carParameters">
                        <table>
                           <tr>
                               <td colspan="2">'.$view->translate('brand').': <strong>'.$model['name'].'</strong></td>
                           </tr>
                        ';
             $html .= '
                           <tr>
                               <td>'.$view->translate('capacity').': <strong>'.$model['capacity'].'</strong></td>
                               <td>'.$view->translate('horse power').': <strong>'.$model['horsepower'].'</strong></td>
                           </tr>
                           <tr>
                               <td>'.$view->translate('v-max').': <strong>'.$model['max_speed'].'</strong></td>
                               <td>'.$view->translate('acceleration').': <strong>'.$model['acceleration'].'</strong></td>
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
    
   public  function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
}
  
?>
