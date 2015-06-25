<?php

class TrainingService extends Service{
    
    protected $trainingTable;
    protected $trainingFactorTable;
    protected $rallyService;
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new TrainingService();
       return self::$instance;
    }
    
    protected $driverSkills = array(
        'composure', 'speed','regularity','reflex','on_gravel' ,'on_tarmac','on_snow','in_rain','form','talent'
    );
    
    protected $driverTrainableSkills = array(
        'composure', 'speed','regularity','reflex','on_gravel' ,'on_tarmac','on_snow','in_rain'
    );
    
    protected $dependentSkills = array(
       'on_gravel' ,'on_tarmac','on_snow','in_rain'
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
    
    protected $pilotTrainableSkills = array(
        'composure', 'dictate_rhytm','diction','route_description'
    );
    
    protected $pilotSkillsWages = array(
        3,4,3,5,8,0
    );
    
    public function __construct(){
        $this->trainingTable = parent::getTable('people','training');
        $this->trainingFactorTable = parent::getTable('people','trainingFactor');
    }
    
    
    
    public function getPerson($id){
        $q = $this->trainingFactorTable->createQuery('tf');
        $q->leftJoin('tf.People');
        $q->addWhere('tf.people_id = ?',$id);
        return $q->fetchOne(array(),Doctrine_Core::HYDRATE_RECORD);
    }
    
    public function getMyTrainingResults($team_id,$date = null){
        $q = $this->trainingTable->createQuery('t');
        $q->leftJoin('t.People p');
        $q->addSelect('t.*,p.*');
        $q->orderBy('km_passed_today/max_available_km_passed_today');
        $q->addWhere('p.team_id = ?',$team_id);
        if($date){
            $q->addWhere('DATE(t.training_date) = ?',$date);
        }
        else{
            $q->addWhere('DATE(t.training_date) = CURDATE()');
        }
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function getLastWeekTrainingResults($team_id){
        $q = $this->trainingTable->createQuery('t');
        $q->leftJoin('t.People p');
        $q->addSelect('t.*,p.*');
        $q->orderBy('km_passed_today/max_available_km_passed_today');
        $q->addWhere('p.team_id = ?',$team_id);
        $q->addWhere('DATE(t.training_date) > DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
        $q->orderBy('t.training_date DESC');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function checkTodayTrainingForPerson($skill,$people_id){
        $q = $this->trainingTable->createQuery('t');
        $q->addWhere('t.people_id = ?',$people_id);
        $q->addWhere('t.skill_name = ?',$skill);
        $q->addWhere('DATE(t.training_date) = CURDATE()');
        return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function getLastSkillTrainingForPerson($skill,$people_id){
        $q = $this->trainingTable->createQuery('t');
        $q->addWhere('t.people_id = ?',$people_id);
        $q->addWhere('t.skill_name = ?',$skill);
        $q->orderBy('t.training_date DESC');
        $q->limit(1);
        return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function calculateTraining($crews,$rallyService){
        $this->rallyService = $rallyService;
        foreach($crews as $crew){
            // driver training
            $driver = $this->getPerson($crew['driver_id']);
            $this->calculateTrainingForPerson($driver['People'],$crew['km_passed'],$crew['rally_id']);
            $pilot = $this->getPerson($crew['pilot_id']);
            if(is_object($pilot))
                $this->calculateTrainingForPerson($pilot['People'],$crew['km_passed'],$crew['rally_id']);
            
            $crewRecord = $rallyService->getCrew($crew['id']);
            $crewRecord->set('training_done',1);
            $crewRecord->save();
        }
    }
    
    public function calculateTrainingForPerson(People_Model_Doctrine_People $person,$km_passed,$rally_id){
                
        if(!strlen($person->active_training_skill)){
            if($person->job=="pilot"){
                $newSkill = $this->pilotTrainableSkills[array_rand($this->pilotTrainableSkills)];
                $person->set('active_training_skill',$newSkill);
                $person->save();
            }
            elseif($person->job=="driver"){
                $newSkill = $this->driverTrainableSkills[array_rand($this->driverTrainableSkills)];
                $person->set('active_training_skill',$newSkill);
                $person->save();
            }
        }
        
        $trainingSkill = $person->active_training_skill;
//        echo "pp";exit;
        if($this->checkTodayTrainingForPerson($trainingSkill,$person['id'])){
            return false;
        }
        
        $skillValue = $person[$trainingSkill];
        if($skillValue>=$person['TrainingFactor'][$trainingSkill."_max"]){
            return false;
        }
        
        $trainingResult = array();
        $trainingResult['people_id'] = $person['id'];
        $trainingResult['skill_name'] = $trainingSkill;
        $trainingResult['current_skill_level'] = $skillValue;
        $trainingResult['training_date'] = date('Y-m-d H:i:s');
        
        // max available km passed today means
        // the number of km which you would get for doing equal amount of km
        // on 100% surface with training skill factor 2
        $trainingResult['max_available_km_passed_today'] = $km_passed * 2;
        
        // if training is set on surface training then 
        // multiply the km passed by % of trained surface skill 
        // to get the real value of km passed on trained surface
        if(in_array($trainingSkill,$this->dependentSkills)){
            
            $surfaceExplode = explode('_',$trainingSkill);
            $surface = $surfaceExplode[1];
            
            $surfacePercentage = $this->rallyService->getRallySurfacePercentage($surface,$rally_id);
            
            $km_passed = $km_passed * ($surfacePercentage / 100);
        }
        
        
        
        // get factor of active training skill
        $training_factor = $person['TrainingFactor'][$trainingSkill];
        
        $km_passed = $training_factor*$km_passed;
        
        $lastTraining = $this->getLastSkillTrainingForPerson($trainingSkill,$person['id']);
        if($lastTraining){
            $kmForNextStar = (float)$lastTraining['km_for_next_star'];
            $newKmForNextStar = $kmForNextStar + $km_passed;
        }
        else{
            $newKmForNextStar = $km_passed;
        }
        
        // check how much km are required for next star
        $kmRequiredForNextStar = $this->trainingLevels[$skillValue+1];
        $returnValue = "";
        
        // promotion to new level of skill
        if($newKmForNextStar >= $kmRequiredForNextStar){
            $trainingResult['skill_promotion'] = 1;
            $trainingResult['km_for_next_star'] = $newKmForNextStar - $kmRequiredForNextStar;
            $person->set($trainingSkill,$skillValue+1);
            $person->save();
        }
        else{
            $trainingResult['skill_promotion'] = 0;
            $trainingResult['km_for_next_star'] = $newKmForNextStar;
        }
        
        $trainingResult['km_passed_today'] = $km_passed;
        
        $trainingResultRow = $this->trainingTable->getRecord();
        $trainingResultRow->fromArray($trainingResult);
        
        $trainingResultRow->save();
        
        return true;
    }
    
   
    
    public function createRandomTrainingForDriver(People_Model_Doctrine_People $driver){
        $driverArray  = array();
        $record = $this->trainingFactorTable->getRecord();
        
        $driverTrainableSkills = $this->driverTrainableSkills;
        
        $driverArray['people_id'] = $driver['id'];
       
        // helper to calculate training blocks
        include(BASE_PATH.'modules/people/services/helper/TrainingBlockDriverHelper.php');
        
        $driverTrainableSkills = $this->driverTrainableSkills;
        
        // helper to calculate training factor
        include(BASE_PATH.'modules/people/services/helper/TrainingFactorDriverHelper.php');
        
        
        $record->fromArray($driverArray);
        $record->save();
    }
    
    public function createRandomTrainingForPilot(People_Model_Doctrine_People $pilot){
        $pilotArray  = array();
        $record = $this->trainingFactorTable->getRecord();
        
        $pilotTrainableSkills = $this->pilotTrainableSkills;
        
        $pilotArray['people_id'] = $pilot['id'];
       
        // helper to calculate training blocks
        include(BASE_PATH.'modules/people/services/helper/TrainingBlockPilotHelper.php');
        
        
        $pilotTrainableSkills = $this->pilotTrainableSkills;
        
        // helper to calculate training factor
        include(BASE_PATH.'modules/people/services/helper/TrainingFactorPilotHelper.php');
        
        
        
        $record->fromArray($pilotArray);
        $record->save();
    }
}
?>
