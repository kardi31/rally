<?php

 /*
    * Blokady treningu 
    * 1 - 2 na 5, reszta na 6
    * 2 - wszystkie na 6
    * 3 - 2 na 6,reszta na 7
    * 4 - 1 na 8, reszta na 7
    * 5 - 2 na 7, reszta na 8
    * 6 - 1 na 9, reszta na 8, 
    * 7 - 3 na 8, reszta na 9
    * 8 - 1 na 8, reszta na 9
    * 9 - 1 na 10, reszta 9
    * 10 - 3 na 10, 2 na 9
     */

         switch($pilot['talent']):
            case 1:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 5;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $pilotArray[$pilotTrainableSkills[$randomSkill2]."_max"] = 5;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill2]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 6;
                endforeach;
                break;
            case 2:
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 7;
                endforeach;
                break;
            case 3:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 6;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $pilotArray[$pilotTrainableSkills[$randomSkill2]."_max"] = 6;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill2]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
               
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 7;
                endforeach;
                break;
            case 4:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 8;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 7;
                endforeach;
                break;
            case 5:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 7;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $pilotArray[$pilotTrainableSkills[$randomSkill2]."_max"] = 7;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill2]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
               
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 8;
                endforeach;
                break;
            case 6:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 9;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 8;
                endforeach;
                break;
            case 7:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 8;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $pilotArray[$pilotTrainableSkills[$randomSkill2]."_max"] = 8;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill2]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                // get third random skill
                $randomSkill3 = rand(0,$elemCount-3);
                $pilotArray[$pilotTrainableSkills[$randomSkill3]."_max"] = 8;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill3]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 9;
                endforeach;
                break;
            case 8:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 8;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 9;
                endforeach;
                break;
            case 9:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 10;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 9;
                endforeach;
                break;
            case 10:
                $elemCount = count($pilotTrainableSkills);
                
                // get random skill id
                $randomSkill = rand(0,$elemCount-1);
                // set random skill max value to 6
                $pilotArray[$pilotTrainableSkills[$randomSkill]."_max"] = 9;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                // get second random skill
                $randomSkill2 = rand(0,$elemCount-2);
                $pilotArray[$pilotTrainableSkills[$randomSkill2]."_max"] = 9;
                
                // remove empty value from array
                unset($pilotTrainableSkills[$randomSkill2]);
                $pilotTrainableSkills = array_values($pilotTrainableSkills);
                
                
                foreach($pilotTrainableSkills as $key):
                    $pilotArray[$key."_max"] = 10;
                endforeach;
                break;
        endswitch;
   