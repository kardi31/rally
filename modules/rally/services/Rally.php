<?php



class RallyService extends Service{
    
    protected $rallyTable;
    protected $crewTable;
    protected $stageResultTable;
    protected $resultTable;
    protected $stageTable;
    protected $surfaceTable;
    protected $friendlyTable;
    protected $friendlyInvitationsTable;
    protected $friendlyParticipantsTable;
    protected $accidentTable;
    protected $surfaces = array('tarmac','gravel','rain','snow');
    protected $minsArray = array('00','15','30','45');
    
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new RallyService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->rallyTable = parent::getTable('rally','rally');
        $this->surfaceTable = parent::getTable('rally','surface');
        $this->crewTable = parent::getTable('rally','crew');
        $this->stageTable = parent::getTable('rally','stage');
        $this->stageResultTable = parent::getTable('rally','stageResult');
        $this->resultTable = parent::getTable('rally','result');
        $this->friendlyTable = parent::getTable('rally','friendly');
        $this->friendlyInvitationsTable = parent::getTable('rally','friendlyInvitations');
        $this->friendlyParticipantsTable = parent::getTable('rally','friendlyParticipants');
        $this->accidentTable = parent::getTable('rally','accident');
    }
    
    public function getPrizesHelper(){
        require_once(BASE_PATH."/modules/rally/services/Prizes.php");
        return PrizesService::getInstance();
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
        $q = $this->stageResultTable->createQuery('sr');
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
        $stageResult = $this->stageResultTable->getRecord();
	
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
    
    public function createRalliesForLeague($league){
        for($i=1;$i<=12;$i++){
            $this->createOneLeagueRally($league,$i);
        }
    }
    
    public function createOneLeagueRally($league,$key){
        
        
        $rallyArray = array();
        
        $rallyArray['name'] = "League ".$league." Rally - #1";
        $rallyArray['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$rallyArray['name']);  
        $rallyArray['date'] = date('Y-m-d',strtotime('+ '.($key-1).' weeks sunday'));
        
        $randomHours = rand(8,14);
        if($randomHours == 8 || $randomHours == 9){
            $randomHours = "0".$randomHours;
        }
        $randomMins = $this->minsArray[array_rand($this->minsArray)];
        $rallyArray['date'] = $rallyArray['date']." ".$randomHours.":".$randomMins.":00";
        
        $rallyArray['active'] = 1;
        
        $randomSurfaces = array_rand($this->surfaces,2);
        $randomSurfacePercentage = TK_Text::float_rand(10,90,2);
        foreach($randomSurfaces as $key => $randomSurfaceId):
            $rallyArray['Surfaces'][$key]['surface'] = $this->surfaces[$randomSurfaceId];
            if($key == 0){
                $rallyArray['Surfaces'][$key]['percentage'] = 100 - $randomSurfacePercentage;
            }
            else{
                $rallyArray['Surfaces'][$key]['percentage'] = $randomSurfacePercentage;
            }
        endforeach;
        
        $rallyArray['league_rally'] = 1;
        $rallyArray['league'] = $league;
        
        $rally = $this->rallyTable->getRecord();
        $rally->fromArray($rallyArray);
        
        $rally->save();
        for($i=0;$i<18;$i++){
            $this->createRandomStage($rally['id'],'Etap '.$i);
        }
        
        return $rally;
        
    }
    
    public function createRandomRally($league = false){
        
       $randomNumber = rand(1000000,10000000);
        
        $rallyArray = array();
        
        $rallyArray['name'] = "Rally_".$randomNumber;
        $rallyArray['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$rallyArray['name']);       
        $randDate = rand(1,100);
        $rallyArray['date'] = date('Y-m-d H:i:s',strtotime('+'.$randDate.' days'));
        $rallyArray['active'] = 1;
        
        $randomSurfaces = array_rand($this->surfaces,2);
        $randomSurfacePercentage = TK_Text::float_rand(10,90,2);
        foreach($randomSurfaces as $key => $randomSurfaceId):
            $rallyArray['Surfaces'][$key]['surface'] = $this->surfaces[$randomSurfaceId];
            if($key == 0){
                $rallyArray['Surfaces'][$key]['percentage'] = 100 - $randomSurfacePercentage;
            }
            else{
                $rallyArray['Surfaces'][$key]['percentage'] = $randomSurfacePercentage;
            }
        endforeach;
        
        if($league){
            $rallyArray['league_rally'] = 1;
            $rallyArray['league'] = $league;
        }
        else{
            $rallyArray['league'] = rand(1,5);
        }
        
        $rally = $this->rallyTable->getRecord();
        $rally->fromArray($rallyArray);
        
        $rally->save();
        for($i=0;$i<18;$i++){
            $this->createRandomStage($rally['id'],'Etap '.$i);
        }
        
        return $rally;
        
    }
    
    public function createRandomBigRally($league = false){
        
       $randomNumber = rand(1000000,10000000);
        
        $rallyArray = array();
        
        $rallyArray['name'] = "Rally_".$randomNumber;
        $rallyArray['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$rallyArray['name']);       
        $randDate = rand(1,100);
        $rallyArray['date'] = date('Y-m-d H:i:s',strtotime('+'.$randDate.' days'));
        $rallyArray['active'] = 1;
        
        $randomSurfaces = array_rand($this->surfaces,2);
        $randomSurfacePercentage = TK_Text::float_rand(10,90,2);
        foreach($randomSurfaces as $key => $randomSurfaceId):
            $rallyArray['Surfaces'][$key]['surface'] = $this->surfaces[$randomSurfaceId];
            if($key == 0){
                $rallyArray['Surfaces'][$key]['percentage'] = 100 - $randomSurfacePercentage;
            }
            else{
                $rallyArray['Surfaces'][$key]['percentage'] = $randomSurfacePercentage;
            }
        endforeach;
        
        if($league){
            $rallyArray['league_rally'] = 1;
            $rallyArray['league'] = $league;
        }
        else{
            $rallyArray['league'] = rand(1,5);
        }
        
        $rallyArray['big_awards'] = 1;
        
        $rally = $this->rallyTable->getRecord();
        $rally->fromArray($rallyArray);
        
        $rally->save();
        for($i=0;$i<18;$i++){
            $this->createRandomStage($rally['id'],'Etap '.$i);
        }
        
        $this->getPrizesHelper()->createBigAward($rally['id'],'car','rand');
        $this->getPrizesHelper()->createBigAward($rally['id'],'premium','rand');
        $this->getPrizesHelper()->createBigAward($rally['id'],'premium','rand');
        
        return $rally;
        
    }
    
    
    
    public function createRandomStage($rally_id,$stage_name){
        $stageArray = array();
        
        $stageArray['name'] = $stage_name;
        $stageArray['rally_id'] = $rally_id;     
        $stageArray['length'] = TK_Text::float_rand(2,30,2);
        
        // rally min time generator
        $timeMin = "00:02:50";
        $timeMax = "00:30:15";
        $timeMinUnix = strtotime($timeMin);
        $timeMaxUnix = strtotime($timeMax);
        $randomTimeUnix = mt_rand($timeMinUnix,$timeMaxUnix);
        $stageArray['min_time'] = date('H:i:s',$randomTimeUnix);
        
        $stage = $this->stageTable->getRecord();
        $stage->fromArray($stageArray);
        
        $stage->save();
        
    }
    
    
    
    public function getRallyStages($id,$field='id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->stageTable->createQuery('s');
        $q->leftJoin('s.Rally r');
        $q->addWhere('r.'.$field.' = ?',$id);
        $q->select('s.*,r.id');
        return $q->execute(array(),$hydrationMode);
    }
    
    public function calculateRallyResult(Rally_Model_Doctrine_Rally $rally){
	$teamService = TeamService::getInstance();
	$leagueService = LeagueService::getInstance();
	$id = $rally['id'];
	$participants = count($rally['Crews']);
        // I call it here to avoid multiple calling on foreach
	$bigAwardsCount = count($rally['BigAwards']);	
        
        $leagueLevel = (int)$rally['league'];
		
        $q = $this->stageResultTable->createQuery('sr');
        $q->leftJoin('sr.Stage s');
	$q->leftJoin('sr.Crew cr');
        $q->select('SEC_TO_TIME(SUM(TIME_TO_SEC(sr.base_time))) as total_time');
        $q->addSelect('count(sr.id) as number_of_stages');
        $q->addSelect('SUM(sr.out_of_race) as out_of_race');
        $q->addSelect('sr.crew_id');
        $q->addSelect('stage_id');
	$q->addSelect('cr.team_id');
        $q->groupBy('crew_id');
        $q->addWhere('s.rally_id = ?',$id);
        $q->orderBy('out_of_race ASC,number_of_stages DESC,total_time ASC');
        $results = $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
        
        foreach($results as $key => $result):
            $result['position'] = $key+1;
            $result['rally_id'] = $id;
            if($result['out_of_race']==1){
                $result['stage_out_number'] = $result['number_of_stages'];
                $result['stage_out_id'] = $result['stage_id'];
            }
            
            $alreadyCalculated = true;
            if(!$rallyResultRecord = $this->getCrewResult($id,$result['crew_id'])){
                $alreadyCalculated = false;
                $rallyResultRecord = $this->resultTable->getRecord();
            }
            
            $rallyResultRecord->fromArray($result);
            $rallyResultRecord->save();
	    if(!$alreadyCalculated){
                if(!$rally['big_awards']){
                    $cashEarned = $this->getPrizesHelper()->calculatePrizeForPlace($result['position'],$leagueLevel,$participants);
                    $teamService->addTeamMoney($result['Crew']['team_id'],$cashEarned,1,'Za zajęcie '.$result['position'].' w rajdzie '.$rally['name']);
                    if($rally['league_rally']){
                        if($result['position']<=10){
                            $leagueService->addTeamPoints($result['Crew']['team_id'],$result['position']);
                        }
                    }
                }
                else{
                    $this->getPrizesHelper()->handleBigAwardForPlace($result['position'],$result['Crew']['team_id'],$rally,$bigAwardsCount);
                }
            }
        endforeach;
        $rally->set('finished',1);
        $rally->save();
    }
    
    public function getCrewResult($rally_id,$crew_id){
        $q = $this->resultTable->createQuery('re');
        $q->addWhere('re.rally_id = ?',$rally_id);
        $q->addWhere('re.crew_id = ?',$crew_id);
        return $q->fetchOne(array(),Doctrine_Core::HYDRATE_RECORD);
    }
    
    public function getRallyResults($id,$field='id'){
        $q = $this->resultTable->createQuery('re');
        $q->addSelect('re.*');
        $q->addSelect('cr.*');
        $q->addSelect('d.*');
        $q->addSelect('p.*');
        $q->addSelect('t.*');
        $q->addSelect('s.*');
        $q->leftJoin('re.Crew cr');
        $q->leftJoin('cr.Driver d');
        $q->leftJoin('cr.Pilot p');
        $q->leftJoin('cr.Team t');
        $q->leftJoin('t.Sponsor s');
        $q->addWhere('re.'.$field.' = ?',$id);
        $q->orderBy('re.position');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    /*
     * Friendly rally
     * 
     */
    
    
    public function getAllFutureFriendlyRallies($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Friendly f');
        $q->addSelect('r.*,f.*');
//	$q->addWhere('r.date > NOW()');
	$q->addWhere('r.friendly = 1');
//	$q->orderBy('r.date');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFriendlyRally($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyTable->createQuery('f');
        $q->leftJoin('f.Rally r');
        $q->leftJoin('f.Participants p');
        $q->leftJoin('p.User u');
        $q->addWhere($field .' = ?',$id);
        $q->select('r.*,f.*,p.*,u.*');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function saveFriendlyRally($values,$user_id){
        $rally = $this->createRandomFriendlyRally($values);
        $friendly = $this->friendlyTable->getRecord();
        $friendly->fromArray($values);
        $friendly->rally_id = $rally['id'];
        $friendly->user_id = $user_id;
        $friendly->save();
        
        return $rally;
    }
    
    public function createRandomFriendlyRally($values){
        $rallyArray = array();
        
        $rallyArray['date'] = TK_Text::timeFormat($values['date']." 14:00",'Y-m-d H:i:s','d-m-Y H:i');
        $rallyArray['name'] = $values['name'];
        $rallyArray['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$values['name']);  
        $rallyArray['active'] = 1;
        $rallyArray['friendly'] = 1;
        
        $randomSurfaces = array_rand($this->surfaces,2);
        $randomSurfacePercentage = TK_Text::float_rand(10,90,2);
        foreach($randomSurfaces as $key => $randomSurfaceId):
            $rallyArray['Surfaces'][$key]['surface'] = $this->surfaces[$randomSurfaceId];
            if($key == 0){
                $rallyArray['Surfaces'][$key]['percentage'] = 100 - $randomSurfacePercentage;
            }
            else{
                $rallyArray['Surfaces'][$key]['percentage'] = $randomSurfacePercentage;
            }
        endforeach;
        
        $rally = $this->rallyTable->getRecord();
        $rally->fromArray($rallyArray);
        
        $rally->save();
        for($i=0;$i<18;$i++){
            $this->createRandomStage($rally['id'],'Stage '.$i);
        }
        
        return $rally;
        
    }
    
    public function getFriendlyInvitedUsers($friendly,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyInvitationsTable->createQuery('fi');
        $q->leftJoin('fi.User u');
        $q->select('u.username,fi.id');
        $q->addWhere('fi.friendly_id = ?',$friendly['id']);
        $q->addWhere('fi.deleted_at IS NULL');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFriendlyInvitedUser($friendly_id,$username,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyInvitationsTable->createQuery('fi');
        $q->leftJoin('fi.User u');
        $q->select('u.username,fi.id');
        $q->addWhere('fi.friendly_id = ?',$friendly_id);
        $q->addWhere('u.username like ?',$username);
        $q->addWhere('fi.deleted_at IS NULL');
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getFriendlyParticipant($friendly_id,$username,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyParticipantsTable->createQuery('fp');
        $q->leftJoin('fp.User u');
        $q->select('u.username,fp.id');
        $q->addWhere('fp.friendly_id = ?',$friendly_id);
        $q->addWhere('u.username like ?',$username);
        $q->addWhere('fp.deleted_at IS NULL');
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function inviteUserToFriendlyRally($friendly,$friendlyUser){
        $invitation = $this->friendlyInvitationsTable->getRecord();
        $invitation->user_id = $friendlyUser['id'];
        $invitation->friendly_id = $friendly['id'];
        $invitation->save();
        return $invitation;
    }
    
    public function saveCrewToFriendlyRally($friendly_id,$crew_id,$user_id){
        $invitation = $this->friendlyParticipantsTable->getRecord();
        $invitation->user_id = $user_id;
        $invitation->friendly_id = $friendly_id;
        $invitation->crew_id = $crew_id;
        $invitation->save();
        return $invitation;
    }
    
    public function removeFriendlyInvite($friendly_id,$username){
        $invite = $this->getFriendlyInvitedUser($friendly_id,$username);
        $invite->delete();
    }
}
?>
