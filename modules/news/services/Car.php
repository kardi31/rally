<?php

class NewsService extends Service{
    
    protected $newsTable;
    protected $newsModelTable;
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new NewsService();
       return self::$instance;
    }
    
    protected static $newsModelSkills = array(
        'capacity', 'horsepower','max_speed','acceleration'
    );
       
    protected static $newsModelSkillsWages = array(
	3,5,4,6
    );
    
    protected static $newsModelSkillsMax = array(
	2000,320,200,2.3
    );
    
    protected static $newsMileageWage = 4;
    
    public function __construct(){
        $this->newsTable = parent::getTable('news','news');
        $this->newsModelTable = parent::getTable('news','newsModels');
    }
    
    public function getNews($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->newsTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getNewsModel($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->newsModelTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getAllDrivers(){
        return $this->newsTable->findAll();
    }
    
    public function getTeamNewss($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->newsTable->createQuery('c');
	$q->select('*');
	$q->leftJoin('c.Model cm');
	$q->addWhere('c.team_id = ?',$team_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFreeNewss(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->newsTable->createQuery('c');
	$q->select('c.id,c.name');
	$q->leftJoin('c.News1Team c1t');
	$q->leftJoin('c.News2Team c2t');
	$q->leftJoin('c.NewsRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('c1t.id = ? or c2t.id = ?',array($team['id'],$team['id']));
	$q->addWhere('r.date NOT like ? or r.date IS NULL',substr($date,0,10)."%");
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFreeNewssFriendly(Team_Model_Doctrine_Team $team,$date,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	$q = $this->newsTable->createQuery('c');
	$q->select('c.id,c.name');
	$q->leftJoin('c.Team t');
	$q->leftJoin('c.NewsRallies cr');
	$q->leftJoin('cr.Rally r');
	$q->addWhere('t.id = ?',$team['id']);
        if(!is_null($date))
            $q->addWhere('r.id IS NULL or (r.friendly != 1 OR (r.friendly = 1 and (r.date NOT like ? or r.date IS NULL)))',substr($date,0,10)."%");
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getRandomNewsModel($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->newsModelTable->createQuery('cm');
        $q->orderBy('rand()');
	$q->limit(1);
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getRandomLeagueNews($league_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->newsModelTable->createQuery('cm');
	$q->addWhere('cm.league = ?',$league_id);
        $q->orderBy('rand()');
	$q->limit(1);
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function createNewTeamNews($model,$team_id){
        if(!$model instanceof News_Model_Doctrine_NewsModels){
            $model = $this->getNewsModel($model);
        }
        $upkeep = 0.15 * $model['price'];
        
        $record = $this->newsTable->getRecord();
	
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
    
    public static function getNewsLate($news){
	// get from people object 
	// only the elements which contains 
	// driver skills
	$newsSkills = array_intersect_key($news['Model']->toArray(), array_flip(self::$newsModelSkills));
	// get the difference between max skill(10) and people skills. Then get % of it and multiply by skill wage
	$props = array_map(function($skills,$wages,$max){ return (1-($max-$skills)/$max)*$wages; }, $newsSkills,self::$newsModelSkillsWages,self::$newsModelSkillsMax);
	
	// different calculation for acceleration because less time is better
	// (1 - (min acceleration / news acceleration)) * wage
	$props[3] = (1-((self::$newsModelSkillsMax[3])/$newsSkills['acceleration'])) * self::$newsModelSkillsWages[3];
	
	// calculate mileage wage
	$props[4] = self::calculateMileageWage($news->toArray());
	
	
	$wages = self::$newsModelSkillsWages;
	
	// add mileage wage to wages
	array_push($wages, self::$newsMileageWage);
	
	// calculate weighted average
	$weightedAverage = array_sum($props)/array_sum($wages);
	
	// multiple by 2.5 because news is the most important thing
	
	$weightedAverage *= 2.5;
	
	// add random factor(+10%/-10% of time)
	$random = TK_Text::float_rand(0.9, 1.1);
	$result = $weightedAverage*$random;
	return $result;
    }
    
    public static function calculateMileageWage($news){
	$mileage = $news['mileage'];
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
    
    public function getNewssForSale($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->newsModelTable->createQuery('cm');
	$q->select('cm.*');
	$q->addWhere('cm.on_market = 1');
	$q->addWhere('cm.price > 0');
	return $q->execute(array(),$hydrationMode);
    }
}
?>
