<?php

class TeamService extends Service{
    
    protected $teamTable;
    
    protected $driverSkills = array(
        'composure', 'speed','regularity','reflex','on_gravel' ,'on_tarmac','on_snow','in_rain','talent'
    );
       
    protected $pilotSkills = array(
        'composure','talent', 'dictate_rhytm','diction','inteligence','route_description'
    );
    
    
    public function __construct(){
        $this->teamTable = parent::getTable('team','team');
    }
    
    public function getAllTeams(){
        return $this->teamTable->findAll();
    }
    
    public function createRandomTeam($values,$user_id = null){
        if($user_id)
            $values['name'] = "Team_".$user_id;
        else
            $values['name'] = $this->generateRandomString();
        
        $record = $this->teamTable->getRecord();
        $record->fromArray($values);
        $record->save();
        
        return $record;
    }
    
    public function createRandomPilot($league){
        $skillsValues = $this->uniqueRandomNumbersWithinRangePilot($league);
        $driverSkills = array_combine($this->pilotSkills, $skillsValues);
        $driverSkills['age'] = rand(18,21);
        $driverSkills['first_name'] = $this->generateRandomString();
        $driverSkills['last_name'] = $this->generateRandomString();
        $driverSkills['form'] = 3;
        $driverSkills['job'] = 'pilot';
        
        $record = $this->peopleTable->getRecord();
        $record->fromArray($driverSkills);
        $record->save();
    }
    
    public function uniqueRandomNumbersWithinRangePilot($league) {
        $groups = count($this->pilotSkills);
        switch($league):
            case 1:
                $max = 9;
                $min = 4;
                $tot = 35;
                break;
            case 2:
                $max = 8;
                $min = 4;
                $tot = 31;
                break;
            case 3:
                $max = 7;
                $min = 4;
                $tot = 27;
                break;
            case 4:
                $max = 7;
                $min = 3;
                $tot = 23;
                break;
            case 5:
                $max = 7;
                $min = 2;
                $tot = 19;
                break;
        endswitch;
        
        
        $numbers = array();
        for($i=0;$i<$groups;$i++):
            $numbers[] = rand($min,$max);
        endfor;
        while(array_sum($numbers)!=$tot):
            if($tot>array_sum($numbers)){
                $column = rand(0,$groups-1);
                if($numbers[$column] < $max):
                    $numbers[$column]++;
                endif;
            }
            else{
                $column = rand(0,$groups-1);
                if($numbers[$column] > $min):
                    $numbers[$column]--;
                endif;
            }
        endwhile;
        shuffle($numbers);
        return $numbers;
    }
    
    public function uniqueRandomNumbersWithinRangeDriver($league) {
        $groups = count($this->driverSkills);
        switch($league):
            case 1:
                $max = 9;
                $min = 4;
                $tot = 45;
                break;
            case 2:
                $max = 8;
                $min = 4;
                $tot = 42;
                break;
            case 3:
                $max = 7;
                $min = 4;
                $tot = 39;
                break;
            case 4:
                $max = 7;
                $min = 3;
                $tot = 36;
                break;
            case 5:
                $max = 7;
                $min = 2;
                $tot = 33;
                break;
        endswitch;
        
        
        $numbers = array();
        for($i=0;$i<$groups;$i++):
            $numbers[] = rand($min,$max);
        endfor;
        while(array_sum($numbers)!=$tot):
            if($tot>array_sum($numbers)){
                $column = rand(0,$groups-1);
                if($numbers[$column] < $max):
                    $numbers[$column]++;
                endif;
            }
            else{
                $column = rand(0,$groups-1);
                if($numbers[$column] > $min):
                    $numbers[$column]--;
                endif;
            }
        endwhile;
        shuffle($numbers);
        return $numbers;
    }
    
    
    
    
}
?>
