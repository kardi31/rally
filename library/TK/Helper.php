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
    
   public static function getRealIpAddr()
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
    
    public static function getCountry($country){
        return self::$countries[$country];
    }
    
    
    public static function getCountries(){
        return self::$countries;
    }
    
    protected static $countries = array
(
	'AF' => 'Afghanistan',
	'AX' => 'Aland Islands',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua And Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BY' => 'Belarus',
	'BE' => 'Belgium',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BO' => 'Bolivia',
	'BA' => 'Bosnia And Herzegovina',
	'BW' => 'Botswana',
	'BV' => 'Bouvet Island',
	'BR' => 'Brazil',
	'IO' => 'British Indian Ocean Territory',
	'BN' => 'Brunei Darussalam',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'KH' => 'Cambodia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'CV' => 'Cape Verde',
	'KY' => 'Cayman Islands',
	'CF' => 'Central African Republic',
	'TD' => 'Chad',
	'CL' => 'Chile',
	'CN' => 'China',
	'CX' => 'Christmas Island',
	'CC' => 'Cocos (Keeling) Islands',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo',
	'CD' => 'Congo, Democratic Republic',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'CI' => 'Cote D\'Ivoire',
	'HR' => 'Croatia',
	'CU' => 'Cuba',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'DK' => 'Denmark',
	'DJ' => 'Djibouti',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'EC' => 'Ecuador',
	'EG' => 'Egypt',
	'SV' => 'El Salvador',
	'GQ' => 'Equatorial Guinea',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'ET' => 'Ethiopia',
	'FK' => 'Falkland Islands (Malvinas)',
	'FO' => 'Faroe Islands',
	'FJ' => 'Fiji',
	'FI' => 'Finland',
	'FR' => 'France',
	'GF' => 'French Guiana',
	'PF' => 'French Polynesia',
	'TF' => 'French Southern Territories',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'DE' => 'Germany',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GR' => 'Greece',
	'GL' => 'Greenland',
	'GD' => 'Grenada',
	'GP' => 'Guadeloupe',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GN' => 'Guinea',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HM' => 'Heard Island & Mcdonald Islands',
	'VA' => 'Holy See (Vatican City State)',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran, Islamic Republic Of',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle Of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KR' => 'Korea',
	'KW' => 'Kuwait',
	'KG' => 'Kyrgyzstan',
	'LA' => 'Lao People\'s Democratic Republic',
	'LV' => 'Latvia',
	'LB' => 'Lebanon',
	'LS' => 'Lesotho',
	'LR' => 'Liberia',
	'LY' => 'Libyan Arab Jamahiriya',
	'LI' => 'Liechtenstein',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'MO' => 'Macao',
	'MK' => 'Macedonia',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MY' => 'Malaysia',
	'MV' => 'Maldives',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MH' => 'Marshall Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'MX' => 'Mexico',
	'FM' => 'Micronesia, Federated States Of',
	'MD' => 'Moldova',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MA' => 'Morocco',
	'MZ' => 'Mozambique',
	'MM' => 'Myanmar',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NL' => 'Netherlands',
	'AN' => 'Netherlands Antilles',
	'NC' => 'New Caledonia',
	'NZ' => 'New Zealand',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NF' => 'Norfolk Island',
	'MP' => 'Northern Mariana Islands',
	'NO' => 'Norway',
	'OM' => 'Oman',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PS' => 'Palestinian Territory, Occupied',
	'PA' => 'Panama',
	'PG' => 'Papua New Guinea',
	'PY' => 'Paraguay',
	'PE' => 'Peru',
	'PH' => 'Philippines',
	'PN' => 'Pitcairn',
	'PL' => 'Poland',
	'PT' => 'Portugal',
	'PR' => 'Puerto Rico',
	'QA' => 'Qatar',
	'RE' => 'Reunion',
	'RO' => 'Romania',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'BL' => 'Saint Barthelemy',
	'SH' => 'Saint Helena',
	'KN' => 'Saint Kitts And Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin',
	'PM' => 'Saint Pierre And Miquelon',
	'VC' => 'Saint Vincent And Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'ST' => 'Sao Tome And Principe',
	'SA' => 'Saudi Arabia',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SK' => 'Slovakia',
	'SI' => 'Slovenia',
	'SB' => 'Solomon Islands',
	'SO' => 'Somalia',
	'ZA' => 'South Africa',
	'GS' => 'South Georgia And Sandwich Isl.',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard And Jan Mayen',
	'SZ' => 'Swaziland',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syrian Arab Republic',
	'TW' => 'Taiwan',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad And Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks And Caicos Islands',
	'TV' => 'Tuvalu',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom',
	'US' => 'United States',
	'UM' => 'United States Outlying Islands',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela',
	'VN' => 'Viet Nam',
	'VG' => 'Virgin Islands, British',
	'VI' => 'Virgin Islands, U.S.',
	'WF' => 'Wallis And Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe',
);
}
  
?>
