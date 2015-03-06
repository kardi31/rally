<?php

 /*
    * Blokady treningu 
    * 1 - 2 na 7, reszta na 6
    * 2 - 4 na 7, reszta na 6
    * 3 - 7 na 7
    * 4 - 2 na 8, reszta na 7
    * 5 - 5 na 8, reszta na 7
    * 6 - 1 na 9, reszta na 8, 
    * 7 - 3 na 8, reszta na 9
    * 8 - 1 na 10 - reszta na 9
    * 9 - 2 na 10, reszta na  9
    * 10 - 5 na 10, 2 na 9
     */

// switch for training blocks
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
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 7;
                endforeach;
                break;
            case 4:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 8;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 8;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
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
                    $driverArray[$key."_max"] = 8;
                endforeach;
                break;
            case 6:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 9;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 8;
                endforeach;
                break;
            case 7:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 8;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 8;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $driverArray[$driverTrainableSkills[$randomSkill3]."_max"] = 8;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill3]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 9;
                endforeach;
                break;
            case 8:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 10;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 9;
                endforeach;
                break;
            case 9:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 10;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 10;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 9;
                endforeach;
                break;
            case 10:
                $elemCount = count($driverTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $driverArray[$driverTrainableSkills[$randomSkill]."_max"] = 9;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $driverArray[$driverTrainableSkills[$randomSkill2]."_max"] = 9;
                
                // remove empty value from array
                unset($driverTrainableSkills[$randomSkill2]);
                $driverTrainableSkills = array_values($driverTrainableSkills);
                
                
                foreach($driverTrainableSkills as $key):
                    $driverArray[$key."_max"] = 10;
                endforeach;
                break;
        endswitch;
   
        
       