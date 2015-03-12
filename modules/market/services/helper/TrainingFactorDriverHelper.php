<?php

 /*
    * Blokady treningu 
    * 1 - suma factor = 3,7
    * 2 - suma factor = 4,2
    * 3 - suma factor = 4,9
    * 4 - suma factor = 5,6
    * 5 - suma factor = 6,3
    * 6 - 1 na 9, reszta na 7
    * 7 - 3 na 8, reszta na 7,9
    * 8 - 1 na 10 - reszta na 8,6
    * 9 - 2 na 10, reszta na  9,7
    * 10 - 5 na 10, 2 na 10,8
     */

        // skill counter
        $skillCounter = count($driverTrainableSkills);
        $min = 0.5;
        $max = 2;
        $skillSum = 10.5;
        
        switch($driver['talent']):
            case 1:
                $skillSum = 3.7;
                break;
            case 2:
                $skillSum = 4.2;
                break;
            case 3:
                $skillSum = 4.9;
                break;
            case 4:
                $skillSum = 5.6;
                break;
            case 5:
                $skillSum = 6.3;
                break;
            case 6:
                $skillSum = 7;
                break;
            case 7:
                $skillSum = 7.9;
                break;
            case 8:
                $skillSum = 8.6;
                break;
            case 9:
                $skillSum = 9.7;
                break;
            case 10:
                $skillSum = 10.8;
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
            $driverArray[$driverTrainableSkills[$key]] = $valueItem;
        }

        
   
        
       