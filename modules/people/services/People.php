<?php

class PeopleService extends Service{
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new PeopleService();
       return self::$instance;
    }
    
    protected $peopleTable;
    
    /* skills settings */
    
    protected $driverSkills = array(
        'composure', 'speed','regularity','reflex','on_gravel' ,'on_tarmac','on_snow','in_rain','form','talent'
    );
       
    protected $driverSkillsWages = array(
        3,5,4,4,6,6,6,6,8,0
    );
     
    protected $pilotSkills = array(
        'composure', 'dictate_rhytm','diction','route_description','form','talent'
    );
    
    protected $pilotSkillsWages = array(
        3,4,3,5,8,0
    );
    
    protected $basicPersonValue = 800000;
    
    public function __construct(){
        $this->peopleTable = parent::getTable('people','people');
    }
    
    public function getAllDrivers(){
        return $this->peopleTable->findAll();
    }
    
    public function getPerson($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->peopleTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getFreeDrivers(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $busyDrivers = $this->getTeamBusyDrivers($team,$date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
        $q->addWhere("p.job like 'driver'");
	$q->addWhere('t.id = ?',$team['id']);
        $q->addWhere('p.on_market = 0');
        if(!empty($busyDrivers)){
            $q->whereNotIn('p.id',$busyDrivers);
        }
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamBusyDrivers(Team_Model_Doctrine_Team $team,$date,$hydrationMode){
        $q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
	$q->leftJoin('p.DriverRallies dr');
	$q->leftJoin('dr.Rally r');
        $q->addWhere("p.job like 'driver'");
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamPeople($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.*,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Team t');
        // to display if the guy is in any team
        $q->leftJoin('p.Driver1Team d1t');
        $q->leftJoin('p.Driver2Team d2t');
        $q->leftJoin('p.Pilot1Team p1t');
        $q->leftJoin('p.Pilot2Team p2t');
        $q->addSelect('d1t.id,d2t.id,p1t.id,p2t.id');
	$q->addWhere('t.id = ?',$team_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamPeopleByRole($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.*,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Team t');
	$q->addWhere('t.id = ?',$team_id);
	$queryResult = $q->execute(array(),$hydrationMode);
        $result = array('Drivers' => array(),'Pilots' => array());
        foreach($queryResult as $person):
            if($person['job']=='driver')
                array_push($result['Drivers'],$person);
            else
                array_push($result['Pilots'],$person);
        endforeach;
        
        return $result;
    }
    
    public function getTeamDrivers($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Team t');
        // to display if the guy is in any team
        $q->addWhere('p.job = "driver"');
	$q->addWhere('t.id = ?',$team_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function prepareTeamDrivers($team_id){
        $drivers = $this->getTeamDrivers($team_id,Doctrine_Core::HYDRATE_ARRAY);
        $result = array();
        foreach($drivers as $driver):
            $result[$driver['id']] = $driver['id']." ".$driver['first_name']." ".$driver['last_name'];
        endforeach;
        return $result;
    }
    
    public function getTeamPilots($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Team t');
        // to display if the guy is in any team
        $q->addWhere('p.job = "pilot"');
	$q->addWhere('t.id = ?',$team_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function prepareTeamPilots($team_id){
        $pilots = $this->getTeamPilots($team_id,Doctrine_Core::HYDRATE_ARRAY);
        $result = array();
        foreach($pilots as $pilot):
            $result[$pilot['id']] = $pilot['id']." ".$pilot['first_name']." ".$pilot['last_name'];
        endforeach;
        return $result;
    }
    
    public function getFreePilots(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $busyDrivers = $this->getTeamBusyPilots($team,$date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
        $q->addWhere("p.job like 'pilot'");
	$q->addWhere('t.id = ?',$team['id']);
        $q->addWhere('p.on_market = 0');
        if(!empty($busyDrivers)){
            $q->whereNotIn('p.id',$busyDrivers);
        }
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamBusyPilots(Team_Model_Doctrine_Team $team,$date,$hydrationMode){
        $q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name');
	$q->leftJoin('p.Team t');
	$q->leftJoin('p.PilotRallies dr');
	$q->leftJoin('dr.Rally r');
        $q->addWhere("p.job like 'pilot'");
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
        $q->groupBy('p.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFreeDriversBig(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        if(!$date)
            return array();
        $busyDrivers = $this->getTeamBusyDriversBig($team,$date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
        $q->addWhere("p.job like 'driver'");
        
        $q->addWhere('p.on_market = 0');
	$q->addWhere('t.id = ?',$team['id']);
        
        
        if(!empty($busyDrivers)){
            $q->whereNotIn('p.id',$busyDrivers);
        }
        
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamBusyDriversBig(Team_Model_Doctrine_Team $team,$date,$hydrationMode){
        $q = $this->peopleTable->createQuery('p');
	$q->select('*');
        $q->addSelect('dr.*');
        $q->addSelect('r.*');
	$q->leftJoin('p.Team t');
	$q->leftJoin('p.DriverRallies dr');
	$q->leftJoin('dr.Rally r');
        $q->addWhere('r.big_awards = 1');
        $q->addWhere("p.job like 'driver'");
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
        $q->groupBy('p.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFreeDriversFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        if(!$date)
            return array();
        $busyDrivers = $this->getTeamBusyDriversFriendly($team,$date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
        $q->addWhere("p.job like 'driver'");
        
        $q->addWhere('p.on_market = 0');
	$q->addWhere('t.id = ?',$team['id']);
        
        
        if(!empty($busyDrivers)){
            $q->whereNotIn('p.id',$busyDrivers);
        }
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamBusyDriversFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode){
        $q = $this->peopleTable->createQuery('p');
	$q->select('*');
        $q->addSelect('dr.*');
        $q->addSelect('r.*');
	$q->leftJoin('p.Team t');
	$q->leftJoin('p.DriverRallies dr');
	$q->leftJoin('dr.Rally r');
        $q->addWhere('r.friendly = 1');
        $q->addWhere("p.job like 'driver'");
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
        $q->groupBy('p.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    
    public function getFreePilotsFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        if(!$date)
            return array();
        $busyDrivers = $this->getTeamBusyPilotsFriendly($team,$date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
        $q->addWhere("p.job like 'pilot'");
	$q->addWhere('t.id = ?',$team['id']);
        
        $q->addWhere('p.on_market = 0');
        if(!empty($busyDrivers)){
            $q->whereNotIn('p.id',$busyDrivers);
        }
        
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamBusyPilotsFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode){
        $q = $this->peopleTable->createQuery('p');
	$q->select('p.id');
	$q->leftJoin('p.Team t');
	$q->leftJoin('p.PilotRallies dr');
	$q->leftJoin('dr.Rally r');
        $q->addWhere("p.job like 'pilot'");
        $q->addWhere('r.friendly = 1');
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
        $q->groupBy('p.id');
	return $q->execute(array(),$hydrationMode);
        
    }
    
    public function getFreePilotsBig(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        if(!$date)
            return array();
        $busyDrivers = $this->getTeamBusyPilotsBig($team,$date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
	$q = $this->peopleTable->createQuery('p');
	$q->select('p.id,CONCAT(p.last_name," ",p.first_name) as name,dr.*,r.*');
	$q->leftJoin('p.Team t');
        $q->addWhere("p.job like 'pilot'");
	$q->addWhere('t.id = ?',$team['id']);
        
        $q->addWhere('p.on_market = 0');
        if(!empty($busyDrivers)){
            $q->whereNotIn('p.id',$busyDrivers);
        }
        
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTeamBusyPilotsBig(Team_Model_Doctrine_Team $team,$date,$hydrationMode){
        $q = $this->peopleTable->createQuery('p');
	$q->select('p.id');
	$q->leftJoin('p.Team t');
	$q->leftJoin('p.PilotRallies dr');
	$q->leftJoin('dr.Rally r');
        $q->addWhere("p.job like 'pilot'");
        $q->addWhere('r.big_awards = 1');
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
        $q->groupBy('p.id');
	return $q->execute(array(),$hydrationMode);
        
    }
    
    public function playerInRally($player){
        $q = $this->peopleTable->createQuery('p');
	$q->select('p.id,dr.id,r.*');
        $q->addSelect('dr.*');
	$q->leftJoin('p.Team t');
        if($player['job'] == 'driver'){
            $q->leftJoin('p.DriverRallies dr');
        }
        else{
            $q->leftJoin('p.PilotRallies dr');
        }
	$q->leftJoin('dr.Rally r');
	$q->addWhere('p.id = ?',$player['id']);
	$q->addWhere('r.date > NOW()');
        $q->addWhere('r.active = 1');
	return $q->fetchOneArray();
    }
    
    /* create people section */
    
    public function createRandomDriver($league,$team_id = false){
        $skillsValues = $this->uniqueRandomNumbersWithinRangeDriver((int)$league);
        $driverSkills = array_combine($this->driverSkills, $skillsValues);
        
        $params = array_map(function($x, $y) { return $x * $y; },
                   $driverSkills, $this->driverSkillsWages);
        $driverSkills['value'] = array_sum($params)*1000*TK_Text::float_rand(0.98,1.02,0);
        $driverSkills['salary'] = 0.007*$driverSkills['value'];
        
        $driverSkills['age'] = rand(18,21);
        $driverSkills['first_name'] = $this->generateRandomPeopleFirstName();
        $driverSkills['last_name'] = $this->generateRandomPeopleLastName();
        $driverSkills['form'] = 3;
        $driverSkills['job'] = 'driver';
        
        if($team_id){
            $driverSkills['team_id'] = $team_id;
        }
        
        $record = $this->peopleTable->getRecord();
        $record->fromArray($driverSkills);
        $record->save();
        $trainingService = TrainingService::getInstance();
        $trainingService->createRandomTrainingForDriver($record);
        return $record;
    }
    
    public function createRandomPilot($league,$team_id = false){
        $skillsValues = $this->uniqueRandomNumbersWithinRangePilot((int)$league);
        $driverSkills = array_combine($this->pilotSkills, $skillsValues);
        
        $params = array_map(function($x, $y) { return $x * $y; },
                   $driverSkills, $this->pilotSkillsWages);
        $driverSkills['value'] = array_sum($params)*1000*TK_Text::float_rand(0.98,1.02,0);
        $driverSkills['salary'] = 0.007*$driverSkills['value'];
        
        $driverSkills['age'] = rand(18,21);
        $driverSkills['first_name'] = $this->generateRandomPeopleFirstName();
        $driverSkills['last_name'] = $this->generateRandomPeopleLastName();
        $driverSkills['form'] = 3;
        $driverSkills['job'] = 'pilot';
        
        if($team_id){
            $driverSkills['team_id'] = $team_id;
        }
        
        $record = $this->peopleTable->getRecord();
        $record->fromArray($driverSkills);
        $record->save();
        
        $trainingService = TrainingService::getInstance();
        $trainingService->createRandomTrainingForPilot($record);
        
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
      
    
    /* stage section */
    
    public function runStageForCrew($stage, $crews, $crewsWithResults,$surfaces){
	$minTime = TK_Text::timeFormat($stage['min_time'], 'i:s','H:i:s');
	$timeElems = explode(':',$minTime);
	$minTimeSeconds = $timeElems[0]*60+$timeElems[1];
	$rallyService = new RallyService();
	
        // for training
        $stageLength = $stage['length'];
        
        $res = array();
	foreach($crews as $key => $crew):
	    $stageResults = array();
	    $late = array();
	    if(in_array($crew['id'],$crewsWithResults)||!is_array($crewsWithResults))
		continue;
	    
	    $late['Driver'][$key] = $this->getDriverLate($crew['Driver']);
	    $late['Pilot'][$key] = $this->getPilotLate($crew['Pilot']);
	    $late['Car'][$key] = CarService::getCarLate($crew['Car']);
	    $accidentProbability = $this->calculateAccidentProbability($crew['Driver'],$crew['Pilot'],$crew['Car'],$surfaces);
            
            $crewAccidentRisk = Rally_Model_Doctrine_Rally::getAccidentRisk($crew['risk']);
            if($accidentProbability < $crewAccidentRisk){
                $accidentProbability = $crewAccidentRisk*0.5;
            }
            else{
                $accidentProbability *= $crewAccidentRisk;
            }
	    
	    // divide by 2 because it would be too much
	    $totalLate[$key] = ($late['Car'][$key] + $late['Driver'][$key] + $late['Pilot'][$key])/1.5;
	    // multiply late by minimum stage time
	    // to get the stage result in seconds
//            var_dump(ceil($totalLate[$key] * $minTimeSeconds)+ceil($totalLate[$key] * $minTimeSeconds)*0.13);
//            var_dump(ceil($totalLate[$key] * $minTimeSeconds));exit;
	    $crewSeconds = ceil($totalLate[$key] * $minTimeSeconds * Rally_Model_Doctrine_Rally::getTimeRisk($crew['risk']));

//            echo (ceil($totalLate[$key] * $minTimeSeconds))." - ".$crewSeconds."<br />";
	    $accident = $rallyService->checkAccident($accidentProbability);
	    if ($accident) {
		if ($accident['damage']==100){
		    $crew['in_race'] = false;
		    $stageResults['out_of_race'] = 1;
                    
                    //calculate random km of accident
                    $quarterStageLength = $stageLength / 8;
                    // calculate km between 1/8 of length and 7/8 of length
                    $accidentKm = round(rand($quarterStageLength,$quarterStageLength*7),2);
                    
                     // for training
                    
                    
                    $prevKmPassed = $crew->get('km_passed');
                    $newKmPassed = $prevKmPassed+$accidentKm;
                    $crew->set('km_passed',$newKmPassed);
		}
		else{
		    $crewSeconds = ($accident['damage'] + 100) * $crewSeconds / 100;
                    
                    // for training
                    $prevKmPassed = $crew->get('km_passed');
                    $newKmPassed = $stageLength+$prevKmPassed;
                    $crew->set('km_passed',$newKmPassed);
		}
	    }
            else{
                $prevKmPassed = $crew->get('km_passed');
                $newKmPassed = $stageLength+$prevKmPassed;
                $crew->set('km_passed',$newKmPassed);
            }
	    // convert seconds to proper time
	    $base_time = gmdate("H:i:s", $crewSeconds);
	    
	    $stageResults['stage_id'] = $stage['id'];
	    $stageResults['crew_id'] = $crew['id'];
	    $stageResults['base_time'] = $base_time;
	    $rallyService->saveStageResult($stageResults,$accident);
	    $crew->save();
	endforeach;
    }
    
   
    
    public function getDriverLate($driver){
	// get from people object 
	// only the elements which contains 
	// driver skills
	$driverSkills = array_intersect_key($driver->toArray(), array_flip($this->driverSkills));
	
	// get the difference between max skill(10) and people skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages){ return ((10-$skills)/10)*$wages; }, $driverSkills,$this->driverSkillsWages);

	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($this->driverSkillsWages);
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.95, 1.05);
	$result = $weightedAverage*$random;
	
	return $result;
    }
    
    public function getPilotLate($pilot){
	// get from people object 
	// only the elements which contains 
	// driver skills
	$driverSkills = array_intersect_key($pilot->toArray(), array_flip($this->pilotSkills));
	// get the difference between max skill(10) and people skills. Then get % of it and multiply by skill wage
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
    
    public function getAllActivePlayersNotCalculated($season){
        $q = $this->peopleTable->createQuery('p');
        $q->leftJoin('p.TrainingFactor tf');
        $q->addWhere('tf.last_season_value_id < ?',$season);
        return $q->execute();
    }
    
    public function calculateNewValuesForAllPlayers($season){
        $people = $this->getAllActivePlayersNotCalculated($season);
        foreach($people as $person):
            $person = $this->calculatePersonValue($person);
            $person->get('TrainingFactor')->set('last_season_value_id',$season);
            $person->save();
        endforeach;
    }
    
    public function calculatePersonValue($person){
	// get from people object 
	// only the elements which contains 
	// people skills
        
        if($person['job']=='driver'){
            $skillsArray = array_flip($this->driverSkills);
            $wagesArray = array_flip($this->driverSkillsWages);
        }
        else{
            $skillsArray = array_flip($this->pilotSkills);
            $wagesArray = array_flip($this->pilotSkillsWages);
        }
	$personSkills = array_intersect_key($person->toArray(), $skillsArray);
	// get the difference between max skill(10) and people skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages){ return ((10-$skills)/10)*$wages; }, $personSkills,$wagesArray);
	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($wagesArray);
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.9, 1.1);
	$result = $weightedAverage*$random;
        
        $value = round($this->basicPersonValue*$result);
        $salary = round($value*0.0151);
        $person->set('value',$value);
        $person->set('salary',$salary);
        return $person;
    }
    
    public function changePersonTeam($id,$team_id){
        $person = $this->getPerson($id);
        $person->set('team_id',$team_id);
        $person->set('on_market',0);
        $person->save();
    }
    public function setOffMarket($id){
        $person = $this->getPerson($id);
        $person->set('on_market',0);
        $person->save();
    }
    
}
?>
