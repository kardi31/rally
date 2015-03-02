<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

         switch($driver['talent']):
            case 1:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 7;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 7;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 6;
                endforeach;
                break;
            case 2:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 3:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 4:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 5:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 6:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 7:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 8:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 9:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 10:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 6;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
        endswitch;
   