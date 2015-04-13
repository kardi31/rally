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
	3,5,4,6
    );
    
    protected static $carModelSkillsMax = array(
	2000,320,200,2.3
    );
    
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
    
    public function getFreeCars(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->carTable->createQuery('c');
	$q->select('c.id,c.name');
	$q->leftJoin('c.Car1Team c1t');
	$q->leftJoin('c.Car2Team c2t');
	$q->leftJoin('c.CarRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('c1t.id = ? or c2t.id = ?',array($team['id'],$team['id']));
	$q->addWhere('r.date NOT like ? or r.date IS NULL',substr($date,0,10)."%");
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
}
?>
