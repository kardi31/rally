<?php

class CarService extends Service{
    
    protected $carTable;
    protected $carModelTable;
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new CarService();
       return self::$instance;
    }
    
    protected static $carModelSkills = array(
        'capacity', 'horsepower','max_speed','acceleration'
    );
       
    protected static $carModelSkillsWages = array(
	6,10,8,12
    );
    
    protected static $carModelSkillsMax = array(
	2000,320,200,2.3
    );
    
    protected $basicCarValue = 20000;
    
    protected static $carMileageWage = 4;
    
    public function __construct(){
        $this->carTable = parent::getTable('car','car');
        $this->carModelTable = parent::getTable('car','carModels');
    }
    
    public function getCar($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->carTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getCarModel($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->carModelTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getAllDrivers(){
        return $this->carTable->findAll();
    }
    
    public function getTeamCars($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->carTable->createQuery('c');
	$q->select('*');
	$q->leftJoin('c.Model cm');
	$q->addWhere('c.team_id = ?',$team_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function prepareTeamCars($team_id){
        $cars = $this->getTeamCars($team_id);
        $result = array();
        foreach($cars as $car):
            $result[$car['id']] = $car['id']." ".$car['name']." - ".$car['Model']['name'];
        endforeach;
        return $result;
    }
    
    public function getFreeCars(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$busyCars = $this->getBusyCars($team, $date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        $q = $this->carTable->createQuery('c');
	$q->select('c.id,c.name');
	$q->leftJoin('c.Team t');
	$q->leftJoin('c.CarRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('t.id = ?',$team['id']);
        if(!empty($busyCars))
            $q->addWhere('c.id NOT IN ?',$busyCars);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getBusyCars(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->carTable->createQuery('c');
	$q->select('c.id,c.name');
	$q->leftJoin('c.Team t');
	$q->leftJoin('c.CarRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('t.id = ?',$team['id']);
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
	return $q->execute(array(),$hydrationMode);
    }
    
     public function getFreeCarsFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$busyCars = $this->getBusyCarsFriendly($team, $date,Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        $q = $this->carTable->createQuery('c');
	$q->select('c.id,c.name');
	$q->leftJoin('c.Team t');
	$q->leftJoin('c.CarRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('t.id = ?',$team['id']);
        if(!empty($busyCars)){
            if(!is_array($busyCars)&&strlen($busyCars)>3){
                $busyCars = array($busyCars);
            }
            $q->addWhere('c.id NOT IN ?',$busyCars);
        }
	return $q->execute(array(),$hydrationMode);
    }
    
     public function getBusyCarsFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->carTable->createQuery('c');
	$q->select('c.id');
	$q->leftJoin('c.Team t');
	$q->leftJoin('c.CarRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('t.id = ?',$team['id']);
        $q->addWhere('r.friendly = 1');
	$q->addWhere('r.date like ?',substr($date,0,10)."%");
        $q->groupBy('c.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getRandomCarModel($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->carModelTable->createQuery('cm');
        $q->orderBy('rand()');
	$q->limit(1);
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getRandomLeagueCar($league_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->carModelTable->createQuery('cm');
	$q->addWhere('cm.league = ?',$league_id);
        $q->orderBy('rand()');
	$q->limit(1);
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function createNewTeamCar($model,$team_id){
        if(!$model instanceof Car_Model_Doctrine_CarModels){
            $model = $this->getCarModel($model);
        }
        $upkeep = 0.15 * $model['price'];
        
        $record = $this->carTable->getRecord();
	
	$data = array(
	  'model_id' => $model['id'],
	  'value' => $model['price'],
          'team_id' => $team_id,
	  'upkeep' => $upkeep,
	  'mileage' => 0,
	  'name' => $model['name']." #".rand(1000,25000)
	);
	
	$record->fromArray($data);
	$record->save();
	
	return $record;
    }
    
    public function calculateCarValue($car){
	// get from people object 
	// only the elements which contains 
	// driver skills
	$carSkills = array_intersect_key($car['Model']->toArray(), array_flip(self::$carModelSkills));
	// get the difference between max skill(10) and people skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages,$max){ return (1-($max-$skills)/$max)*$wages; }, $carSkills,self::$carModelSkillsWages,self::$carModelSkillsMax);
	
	// different calculation for acceleration because less time is better
	// (1 - (min acceleration / car acceleration)) * wage
	$props[3] = (1-((self::$carModelSkillsMax[3])/$carSkills['acceleration'])) * self::$carModelSkillsWages[3];
	
	// calculate mileage wage
	$props[4] = self::calculateMileageWage($car->toArray());
	
	
	$wages = self::$carModelSkillsWages;
	
	// add mileage wage to wages
	array_push($wages, self::$carMileageWage);
	
	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($wages);
	
	// multiple by 2.5 because car is the most important thing
	
	$weightedAverage *= 2.5;
	
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.9, 1.1);
	$result = $weightedAverage*$random;
        
        $value = round($this->basicCarValue*$result);
        $upkeep = round($value*0.0531);
        $car->set('value',$value);
        $car->set('upkeep',(int)$upkeep);
        $car->save();
	return $car;
    }
    
    public static function getCarLate($car){
	// get from people object 
	// only the elements which contains 
	// driver skills
	$carSkills = array_intersect_key($car['Model']->toArray(), array_flip(self::$carModelSkills));
	// get the difference between max skill(10) and people skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages,$max){ return (1-($max-$skills)/$max)*$wages; }, $carSkills,self::$carModelSkillsWages,self::$carModelSkillsMax);
	
	// different calculation for acceleration because less time is better
	// (1 - (min acceleration / car acceleration)) * wage
	$props[3] = (1-((self::$carModelSkillsMax[3])/$carSkills['acceleration'])) * self::$carModelSkillsWages[3];
	
	// calculate mileage wage
	$props[4] = self::calculateMileageWage($car->toArray());
	
	
	$wages = self::$carModelSkillsWages;
	
	// add mileage wage to wages
	array_push($wages, self::$carMileageWage);
	
	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($wages);
	
	// multiple by 2.5 because car is the most important thing
	
	$weightedAverage *= 2.5;
	
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.9, 1.1);
	$result = $weightedAverage*$random;
	return $result;
    }
    
    public static function calculateMileageWage($car){
	$mileage = $car['mileage'];
	if($mileage<50000){
	    return 0;
	}elseif($mileage<70000){
	    return 2.5;
	}elseif($mileage<100000){
	    return 5;
	}elseif($mileage<120000){
	    return 10;
	}elseif($mileage<150000){
	    return 20;
	}else{
	    return 30;
	}
    }
    
    public function getCarsForSale($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->carModelTable->createQuery('cm');
	$q->select('cm.*');
	$q->addWhere('cm.on_market = 1');
	$q->addWhere('cm.price > 0');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function saveCarModel($values){
        if(!(isset($values['id'])&&$record = $this->getCarModel($values['id']))){
            $record = $this->carModelTable->getRecord();
        }
	$record->fromArray($values);
	$record->save();
	
	return $record;
    }
    
    
    public function getAllActiveCarsNotCalculated($season){
        $q = $this->carTable->createQuery('c');
        $q->addWhere('c.last_season_value_id < ?',$season);
        return $q->execute();
    }
    
    public function calculateNewValuesForAllCars($season){
        $cars = $this->getAllActiveCarsNotCalculated($season);
        foreach($cars as $car):
            $car = $this->calculateCarValue($car);
            $car->set('last_season_value_id',$season);
            $car->save();
        endforeach;
    }
}
?>
