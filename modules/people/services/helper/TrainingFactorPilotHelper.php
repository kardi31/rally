<?php

 /*
    * Blokady treningu 
    * 1 - suma factor = 2.7
    * 2 - suma factor = 3,0
    * 3 - suma factor = 3,5
    * 4 - suma factor = 4,0
    * 5 - suma factor = 4.5
    * 6 - 1 na 9, reszta na 5
    * 7 - 3 na 8, reszta na 5,4
    * 8 - 1 na 10 - reszta na 6
    * 9 - 2 na 10, reszta na  6.7
    * 10 - 5 na 10, 2 na 7.7
     */

        // skill counter
        $skillCounter = count($pilotTrainableSkills);
        $min = 0.5;
        $max = 2;
        
        switch($pilot['talent']):
            case 1:
                $skillSum = 2.7;
                break;
            case 2:
                $skillSum = 3.0;
                break;
            case 3:
                $skillSum = 3.5;
                break;
            case 4:
                $skillSum = 4.0;
                break;
            case 5:
                $skillSum = 4.5;
                break;
            case 6:
                $skillSum = 5;
                break;
            case 7:
                $skillSum = 5.4;
                break;
            case 8:
                $skillSum = 6;
                break;
            case 9:
                $skillSum = 6.7;
                break;
            case 10:
                $skillSum = 7.6;
                break;
        endswitch;
        $values = array();
        for($i=0;$i<$skillCounter;$i++){
            $values[] = TK_Text::float_rand($min,$max,2);
        }
        
        // generate random sum of $skillCounter
        if(array_sum($values)-$skillSum>=0.3){
            while(array_sum($values)-$skillSum>=0.3){
                $randomColumn = rand(0,$skillCounter-1);
                echo (array_sum($values)-$skillSum)." - 1<br />";
                if($values[$randomColumn] >= 0.6){
                    $values[$randomColumn] -= 0.1;
                }
                elseif($values[$randomColumn] > 0.5){
                    $values[$randomColumn] = $values[$randomColumn] - ($values[$randomColumn] - 0.5);
                }
            }
        }
        else{
            while(array_sum($values)-$skillSum<=0.3){
                $randomColumn = rand(0,$skillCounter-1);
                if($values[$randomColumn] <= 1.9){
                    $values[$randomColumn] += 0.1;
                }
                elseif($values[$randomColumn] < 2){
                    $values[$randomColumn] = $values[$randomColumn] + (2 - $values[$randomColumn]);
                } 
            }
        }

        foreach($values as $key=>$valueItem){
            $pilotArray[$pilotTrainableSkills[$key]] = $valueItem;
        }

        
   
        
       