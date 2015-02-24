<?php

class TrainingService extends Service{
    
    protected $trainingTable;
    
    protected $driverSkills = array(
        'composure', 'speed','regularity','reflex','on_gravel' ,'on_tarmac','on_snow','in_rain','form','talent'
    );
       
    // dla wspolczynnika 1
    protected $trainingLevels = array(
        2 => 100,
        3 => 130,
        4 => 175,
        5 => 245,
        6 => 355,
        7 => 532,
        8 => 824,
        9 => 1318,
        10 => 2174
    );
     
    /*
     * kalkulator dla wsp 0.5
     * 1/0.5 = x2
     * dla wsp 2
     * 1/2 = /2
     */
    
    protected $pilotSkills = array(
        'composure', 'dictate_rhytm','diction','route_description','form','talent'
    );
    
    protected $pilotSkillsWages = array(
        3,4,3,5,8,0
    );
    
    public function __construct(){
//        $this->trainingTable = parent::getTable('training','training');
    }
    
    public function getAllDrivers(){
        return $this->trainingTable->findAll();
    }
    
    public function getFreeDrivers(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->trainingTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Driver1Team d1t');
	$q->leftJoin('p.Driver2Team d2t');
	$q->leftJoin('p.DriverRallies dr');
	$q->leftJoin('dr.Rally r');
	$q->addWhere('d1t.id = ? or d2t.id = ?',array($team['id'],$team['id']));
	$q->addWhere('r.date NOT like ? or r.date IS NULL',substr($date,0,10)."%");
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFreePilots(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->trainingTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Pilot1Team p1t');
	$q->leftJoin('p.Pilot2Team p2t');
	$q->leftJoin('p.PilotRallies dr');
	$q->leftJoin('dr.Rally r');
	$q->addWhere('p1t.id = ? or p2t.id = ?',array($team['id'],$team['id']));
	$q->addWhere('r.date NOT like ? or r.date IS NULL',substr($date,0,10)."%");
	return $q->execute(array(),$hydrationMode);
    }
    
    
    
    public function createRandomDriver($league){
        $skillsValues = $this->uniqueRandomNumbersWithinRangeDriver($league);
        $driverSkills = array_combine($this->driverSkills, $skillsValues);
        $driverSkills['age'] = rand(18,21);
        $driverSkills['first_name'] = $this->generateRandomTrainingFirstName();
        $driverSkills['last_name'] = $this->generateRandomTrainingLastName();
        $driverSkills['form'] = 3;
        $driverSkills['job'] = 'driver';
        
        $record = $this->trainingTable->getRecord();
        $record->fromArray($driverSkills);
        $record->save();
        
        return $record;
    }
    
    public function createRandomPilot($league){
        $skillsValues = $this->uniqueRandomNumbersWithinRangePilot($league);
        $driverSkills = array_combine($this->pilotSkills, $skillsValues);
        $driverSkills['age'] = rand(18,21);
        $driverSkills['first_name'] = $this->generateRandomTrainingFirstName();
        $driverSkills['last_name'] = $this->generateRandomTrainingLastName();
        $driverSkills['form'] = 3;
        $driverSkills['job'] = 'pilot';
        
        $record = $this->trainingTable->getRecord();
        $record->fromArray($driverSkills);
        $record->save();
        
        return $record;
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
                $tot = 55;
                break;
            case 2:
                $max = 8;
                $min = 4;
                $tot = 48;
                break;
            case 3:
                $max = 7;
                $min = 4;
                $tot = 41;
                break;
            case 4:
                $max = 7;
                $min = 3;
                $tot = 34;
                break;
            case 5:
                $max = 7;
                $min = 2;
                $tot = 27;
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
    
    
    public function runStageForCrew($stage, $crews, $crewsWithResults,$surfaces){
	$minTime = TK_Text::timeFormat($stage['min_time'], 'i:s','H:i:s');
	$timeElems = explode(':',$minTime);
	$minTimeSeconds = $timeElems[0]*60+$timeElems[1];
	$rallyService = new RallyService();
	
//        var_dump($crews);exit;
	foreach($crews as $key => $crew):
	    $stageResults = array();
	    $late = array();
	    if(in_array($crew['id'],$crewsWithResults))
		continue;
	    
	    $late['Driver'][$key] = $this->getDriverLate($crew['Driver']);
	    $late['Pilot'][$key] = $this->getPilotLate($crew['Pilot']);
	    $late['Car'][$key] = CarService::getCarLate($crew['Car']);
	    $accidentProbability = $this->calculateAccidentProbability($crew['Driver'],$crew['Pilot'],$crew['Car'],$surfaces);
	    
	    $accidentProbability *= Rally_Model_Doctrine_Rally::getAccidentRisk($crew['risk']);
	    
	    // divide by 2 because it would be too much
	    $totalLate[$key] = ($late['Car'][$key] + $late['Driver'][$key] + $late['Pilot'][$key])/1.5;
	    // multiply late by minimum stage time
	    // to get the stage result in seconds
	    $crewSeconds = ceil($totalLate[$key] * $minTimeSeconds * Rally_Model_Doctrine_Rally::getTimeRisk($crew['risk']));
	    
	    $accident = $rallyService->checkAccident($accidentProbability);
	    
	    if ($accident) {
		$stageResults['Accident'] = $accident['id'];
		
		if ($accident['damage']==100){
		    $crew['in_race'] = false;
		    $stageResults['out_of_race'] = 1;
		}
		else{
		    $crewSeconds = ($accident['damage'] + 100) * $crewSeconds / 100;
		}
	    }
	    // convert seconds to proper time
	    $base_time = gmdate("H:i:s", $crewSeconds);
	    
	    $stageResults['stage_id'] = $stage['id'];
	    $stageResults['crew_id'] = $crew['id'];
	    $stageResults['base_time'] = $base_time;
	    $rallyService->saveStageResult($stageResults);
	    $crew->save();
	endforeach;
    }
    
   
    
    public function getDriverLate($driver){
	// get from training object 
	// only the elements which contains 
	// driver skills
	$driverSkills = array_intersect_key($driver->toArray(), array_flip($this->driverSkills));
	
	// get the difference between max skill(10) and training skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages){ return ((10-$skills)/10)*$wages; }, $driverSkills,$this->driverSkillsWages);
	
	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($this->driverSkillsWages);
	
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.9, 1.1);
	$result = $weightedAverage*$random;
	
	return $result;
    }
    
    public function getPilotLate($pilot){
	// get from training object 
	// only the elements which contains 
	// driver skills
	$driverSkills = array_intersect_key($pilot->toArray(), array_flip($this->pilotSkills));
	// get the difference between max skill(10) and training skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages){ return ((10-$skills)/10)*$wages; }, $driverSkills,$this->pilotSkillsWages);
	
	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($this->pilotSkillsWages);
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.9, 1.1);
	$result = $weightedAverage*$random;
	
	return $result;
    }
    
    public function calculateAccidentProbability($driver, $pilot, $car, $surfaces){
	$accidentProbability = 0;
	
	// driver accident skills
	if($driver['reflex']<4)
	    $accidentProbability += 0.5;
	
	foreach($surfaces as $surface):
	    if($surface['surface']=="rain")
		$skill = $driver['in_'.$surface['surface']];
	    else
		$skill = $driver['on_'.$surface['surface']];
	
	    if($skill<4){
		// probability rise with 3 if skill is less than 4
		// get % of surface and multiply it by probability to get the result
		$accidentProbability += 3*($surface['percentage']/100);
	    }
	endforeach;
	
	// pilot accident skills
	if($pilot['dictate_rhytm']<4)
	    $accidentProbability += 0.5;
	
	
	if($pilot['route_description']<4)
	    $accidentProbability += 0.5;
	
	
	// car accident skills
	
	$mileage = $car['mileage'];
	if($mileage<50000){
	    $accidentProbability += 0;
	}elseif($mileage<70000){
	    $accidentProbability += 1;
	}elseif($mileage<100000){
	    $accidentProbability += 2;
	}elseif($mileage<120000){
	    $accidentProbability += 4;
	}elseif($mileage<150000){
	    $accidentProbability += 6;
	}else{
	    $accidentProbability += 10;
	}
	
	return $accidentProbability;
    }
    
    
}
?>