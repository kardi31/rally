<?php

class RallyService extends Service{
    
    protected $rallyTable;
    protected $crewTable;
    protected $resultTable;
    protected $stageTable;
    protected $surfaceTable;
    protected $accidentTable;
    
    
    public function __construct(){
        $this->rallyTable = parent::getTable('rally','rally');
        $this->surfaceTable = parent::getTable('rally','surface');
        $this->crewTable = parent::getTable('rally','crew');
        $this->stageTable = parent::getTable('rally','stage');
        $this->resultTable = parent::getTable('rally','stageResult');
        $this->accidentTable = parent::getTable('rally','accident');
    }
    
    public function getAllRallies(){
        return $this->rallyTable->findAll();
    }
    
    public function getRally($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->rallyTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getCrew($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->crewTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getStage($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->stageTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getAllFutureRallies($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
	$q->addWhere('r.date > NOW()');
	$q->orderBy('r.date');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllSurfaces($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $surface = array(
		'gravel' => 'Gravel',
		'tarmac' => 'Tarmac',
		'rain' => 'Rain',
		'snow' => 'Snow');
 	return $surface;
    }
    
    public function getStageWithResults($id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->stageTable->createQuery('s');
	$q->leftJoin('s.Results sr');
	$q->leftJoin('sr.Accident a');
	$q->leftJoin('sr.Crew c');
	$q->leftJoin('c.Pilot p');
	$q->leftJoin('c.Car ca');
	$q->leftJoin('c.Team t');
	$q->leftJoin('c.Driver d');
	$q->addWhere('s.id = ?',$id);
	$q->addSelect('s.*,sr.*,c.*,p.*,ca.*,d.*,t.*,a.*');
	$q->orderBy('sr.base_time');
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getStageShort($id,$field,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->stageTable->createQuery('s');
	$q->addWhere('s.'.$field.' = ?',$id);
	$q->addSelect('s.*');
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    
     public function getStageWithRally($id,$field,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->stageTable->createQuery('s');
	$q->leftJoin('s.Rally r');
	$q->addWhere('s.'.$field.' = ?',$id);
	$q->addSelect('s.*,r.*');
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getCrewsWithoutResults($stage_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->resultTable->createQuery('sr');
	$q->select('sr.crew_id');
	$q->addWhere('sr.stage_id = ?',$stage_id);
	$q->addWhere('sr.base_time IS NOT NULL');
	$q->addWhere('sr.out_of_race = 0');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getRallySurfaces($rally_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->surfaceTable->createQuery('su');
	$q->select('su.*');
	$q->addWhere('su.rally_id = ?',$rally_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getRallyWithCrews($id,$field,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
	$q->leftJoin('r.Crews c');
	$q->addWhere('r.'.$field." like '".$id."'");
	$q->addWhere('r.date > NOW()');
	$q->addWhere('c.in_race = 1');
	$q->orderBy('r.date');
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getRallyCrews($id,$field,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->crewTable->createQuery('cr');
	$q->leftJoin('cr.Pilot p');
	$q->leftJoin('cr.Driver d');
	$q->leftJoin('cr.Car c');
	$q->leftJoin('c.Model cm');
	$q->addWhere('cr.'.$field." = ".$id);
	$q->addWhere('cr.in_race = 1');
	$q->addSelect('cr.*,p.*,d.*,c.*,cm.*');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function saveRallyCrew($values,$rally,$team){
        $record = $this->crewTable->getRecord();
	
	// we must encode risk to get float value, not a string
	$riskArray = Rally_Model_Doctrine_Rally::getRisks();
	
	$values['risk'] = array_search($values['risk'], $riskArray);
	$values['rally_id'] = $rally['id'];
	$values['team_id'] = $team['id'];
	$record->fromArray($values);
	$record->save();
	
	return $record;
    }
    
    public function saveRally($values){
        $rally = $this->rallyTable->getRecord();
	
	$values['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$values['name']);
	$rally->fromArray($values);
	
	$rally->save();
	$rally['Surfaces']->delete();
	for($i=1;$i<4;$i++):
	    if(!strlen($values['surface'.$i])||$values['percent'.$i]==0){
		break;
	    }
		    
	    $data = array();
	    $data['surface'] = $values['surface'.$i];
	    $data['percentage'] = $values['percent'.$i];
	    $data['rally_id'] = $rally['id'];
	    $this->saveSurface($data);
	    
	endfor;
	$rally->save();
	    
	return $rally;
    }
    
    
    public function saveStage($values){
        $stage = $this->stageTable->getRecord();
	
	$stage->fromArray($values);
	$stage->save();
	
	return $stage;
    }
    
    public function saveStageResult($values){
        $stageResult = $this->resultTable->getRecord();
	
	$stageResult->fromArray($values);
	$stageResult->save();
	
	return $stageResult;
    }
    
    public function saveSurface($values){
	$surface = $this->surfaceTable->getRecord();
	$surface->fromArray($values);
	$surface->save();
	
	return $surface;
    }
    
    public function checkAccident($accidentProbability){
	// check if there is accident
	// count random numer (0-100)
	// and check if it's greater than the probability
	$randomNumber = rand(0,100);
	
	// there was an accident
	if ( $randomNumber<=$accidentProbability){
	    $q = $this->accidentTable->createQuery('acc');
	    $q->select('acc.*');
	    $q->orderBy('rand()');
	    $q->limit(1);
	    return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
	    
	}
	else {  
	    return false;
	}
	
    }
    
    public function getCrewsWithNotCompletedTrainingToday($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->crewTable->createQuery('c');
        $q->leftJoin('c.Rally r');
        $q->leftJoin('c.Driver d');
        $q->leftJoin('c.Pilot p');
//        $q->leftJoin('r.Surfaces sf');
        $q->addWhere('DATE(date) = CURDATE()');
        $q->addWhere('c.training_done = 0');
        $q->addSelect('c.*,r.id');
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getRallySurfacePercentage($surface,$rally_id){
        $q = $this->surfaceTable->createQuery('sf');
        $q->addWhere('sf.surface = ?',$surface);
        $q->addWhere('sf.rally_id = ?',$rally_id);
        $q->select('sf.percentage');
        return $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
}
?>
