<?php

class RallyService extends Service{
    
    protected $rallyTable;
    protected $crewTable;
    protected $stageTable;
    protected $surfaceTable;
    protected $accidentTable;
    
    
    public function __construct(){
        $this->rallyTable = parent::getTable('rally','rally');
        $this->surfaceTable = parent::getTable('rally','surface');
        $this->crewTable = parent::getTable('rally','crew');
        $this->stageTable = parent::getTable('rally','stage');
        $this->accidentTable = parent::getTable('rally','accident');
    }
    
    public function getAllRallies(){
        return $this->rallyTable->findAll();
    }
    
    public function getRally($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->rallyTable->findOneBy($field,$id,$hydrationMode);
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
    
    public function getRallyWithCrews($id,$field,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
	$q->leftJoin('r.Crews c');
	$q->addWhere('r.'.$field." like '".$id."'");
	$q->addWhere('r.date > NOW()');
	$q->addWhere('c.in_race = 1');
	$q->orderBy('r.date');
	return $q->fetchOne(array(),$hydrationMode);
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
    
}
?>
