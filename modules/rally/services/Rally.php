<?php



class RallyService extends Service{
    
    protected $rallyTable;
    protected $crewTable;
    protected $stageResultTable;
    protected $resultTable;
    protected $stageTable;
    protected $bigAwardsTable;
    protected $surfaceTable;
    protected $awardTable;
    protected $friendlyTable;
    protected $friendlyInvitationsTable;
    protected $friendlyParticipantsTable;
    protected $accidentTable;
    protected $surfaces = array('tarmac','gravel','rain','snow');
    protected $leagueSurfaces = array('tarmac','gravel','snow');
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
        $this->awardTable = parent::getTable('rally','award');
        $this->stageResultTable = parent::getTable('rally','stageResult');
        $this->resultTable = parent::getTable('rally','result');
        $this->friendlyTable = parent::getTable('rally','friendly');
        $this->bigAwardsTable = parent::getTable('rally','bigAwards');
        $this->friendlyInvitationsTable = parent::getTable('rally','friendlyInvitations');
        $this->friendlyParticipantsTable = parent::getTable('rally','friendlyParticipants');
        $this->accidentTable = parent::getTable('rally','accident');
    }
    
    public function getPrizesHelper(){
        require_once(BASE_PATH."/modules/rally/services/Prizes.php");
        return PrizesService::getInstance();
    }
    
    public function getAllRallies($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->rallyTable->findAll($hydrationMode);
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
    
    public function getAllFutureRallies($hydrationMode = Doctrine_Core::HYDRATE_RECORD,$league = true,$friendly = true){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Crews c');
        $q->addSelect('r.*,c.*');
	$q->addWhere('r.date > NOW()');
	$q->orderBy('r.date','r.level ASC');
        if(!$league){
            $q->addWhere('r.league_rally != 1');
        }
        
        if(!$friendly){
            $q->addWhere('r.friendly != 1');
        }
        $q->limit(20);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllLeagueRallyResults($league_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        
        $seasonInfo = LeagueService::getInstance()->getSeasonInfo();
        
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Results re');
        $q->leftJoin('re.Crew c');
	$q->orderBy('r.date');
        $q->addWhere('r.league_rally = 1');
        $q->addWhere('r.date > ?',$seasonInfo['season_start']);
        $q->addWhere('r.date < ?',$seasonInfo['season_finish']);
        $q->addWhere('r.league like ?',$league_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function prepareResultsForLeague($league_id){
        $results = $this->getAllLeagueRallyResults($league_id,Doctrine_Core::HYDRATE_ARRAY);
        $ralliesResults = array();
        
        // for every rally
        foreach($results as $key=>$result){
            $ralliesResults[$result['id']] = array();
            $ralliesResults[$result['id']]['name'] = "Rally ".($key+1);
            $ralliesResults[$result['id']]['teams'] = array();
            // for every rally result row
            foreach($result['Results'] as $ralResult):
                $crew = $ralResult['Crew'];
                $position = $ralResult['position'];
                // calculate points for place
                $points = $this->getPrizesHelper()->calculatePointsForPlace($position);
                // teams can sign up multiple crews
                // so if more than 1 crew, sum points
                if(isset($ralliesResults[$result['id']]['teams'][$crew['team_id']])){
                    $ralliesResults[$result['id']]['teams'][$crew['team_id']] += $points;
                }
                else{
                    $ralliesResults[$result['id']]['teams'][$crew['team_id']] = $points;
                }
            endforeach;
        }
//        Zend_Debug::dump($ralliesResults);exit;
        return $ralliesResults;
    }
    
    public function getAllFutureLeagueRallies($league_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->addSelect('r.*');
        $q->addSelect('c.*');
        $q->leftJoin('r.Crews c');
	$q->orderBy('r.date');
        $q->addWhere('r.league_rally = 1');
        $q->addWhere('r.date > NOW()');
        $q->limit(4);
        $q->addWhere('r.league like ?',$league_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllTeamRallies($team_id,$limit = 30,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Crews c');
//	$q->addWhere('r.date > NOW()');
//        $q->addWhere('r.friendly = 0');
	$q->orderBy('r.date DESC');
        $q->limit($limit);
        $q->addWhere('c.team_id = ?',$team_id);
        $q->select('r.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllFutureTeamRallies($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Crews c');
        $q->addSelect('r.*');
        $q->addSelect('c.*');
	$q->addWhere('r.date > NOW()');
	$q->orderBy('r.date');
        $q->addWhere('c.team_id = ?',$team_id);
        $q->select('r.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllFutureTeamLeagueRallies($team,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Crews c');
	$q->orderBy('r.date');
        $q->addWhere('r.league_rally = 1');
        $q->addWhere('r.league = ?',$team['league_name']);
        $q->addWhere('c.team_id = ?',$team['id']);
        $q->select('r.id');
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
    
    public function getRalliesWithNotFinishedStages($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
	$q->leftJoin('r.Stages s');
	$q->addWhere('s.date <= NOW()');
	$q->addWhere('s.finished = 0');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getRalliesToFinish($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
	$q->innerJoin('r.Stages s');
        $q->groupBy('r.id');
        $q->select('count(r.id) as cnt');
        $q->addSelect('sum(s.finished) as fin');
        $q->addSelect('r.*,s.*');
	$q->having('cnt = fin');
	$q->addWhere('r.finished = 0');
        $q->addWhere('r.date < NOW()');
        
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getRallySurfaces($rally_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->surfaceTable->createQuery('su');
	$q->select('su.*');
	$q->addWhere('su.rally_id = ?',$rally_id);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function hasRallyNow($team_id){
        $q = $this->rallyTable->createQuery('r');
	$q->leftJoin('r.Crews c');
	$q->leftJoin('r.Stages s');
        $q->addWhere('s.date < ?',date('Y-m-d H:i:s',strtotime('+15 minutes')));
        $q->addWhere('s.date > ?',date('Y-m-d H:i:s',strtotime('-15 minutes')));
	$q->addWhere('c.team_id = ?',$team_id);
	$q->addWhere('r.active = 1');
	$q->addWhere('r.finished = 0');
        $q->select('r.slug');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
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
	
	
        $peopleService = Controller::getService('people','people');
        $carService = Controller::getService('car','car');
        
        // if someone entered player/car id by firebug
        // we check if player/car actually belongs to the team
        if(!$peopleService->getTeamPlayer($values['driver_id'],$team['id'])){
            throw new TK_Exception('Hacking attempt',404);
        }
        
        if(!$peopleService->getTeamPlayer($values['pilot_id'],$team['id'])){
            throw new TK_Exception('Hacking attempt',404);
        }
        
        if(!$carService->getTeamCar($values['car_id'],$team['id'])){
            throw new TK_Exception('Hacking attempt',404);
        }
        
        // check if someone did not change risk value with firebug
        if(!in_array($values['risk'],Rally_Model_Doctrine_Rally::getRiskValues())){
            throw new TK_Exception('Hacking attempt',404);
        }
        
	$values['risk'] = $values['risk'];
	$values['rally_id'] = $rally['id'];
	$values['team_id'] = $team['id'];
	$record->fromArray($values);
//        var_dump($record->toArray());exit;
	$record->save();
	
	return $record;
    }
    
    public function saveLeagueRally($values){
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
    
    public function saveStageResult($values,$accident = false){
        $stageResult = $this->stageResultTable->getRecord();
	
	$stageResult->fromArray($values);
        if($accident&&(int)$accident>0){
            $stageResult->set('accident_id',$accident['id']);
        }
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
        $q->addWhere('DATE(r.date) < CURDATE()');
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
    
    public function createRalliesForLeague($league,$start_date){
        for($i=1;$i<=12;$i++){
            $this->createOneLeagueRally($league,$i,$start_date);
        }
    }
    
    public function createOneLeagueRally($league,$key,$start_date){
        
        $rallyArray = array();
        
        $rallyArray['name'] = "League ".$league." Rally - #".$key;
        $rallyArray['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$rallyArray['name']);  
        $rallyArray['date'] = date('Y-m-d',strtotime($start_date.' + '.($key-1).' weeks sunday'));
        $randomHours = rand(8,14);
        if($randomHours == 8 || $randomHours == 9){
            $randomHours = "0".$randomHours;
        }
        $randomMins = $this->minsArray[array_rand($this->minsArray)];
        $rallyArray['date'] = $rallyArray['date']." ".$randomHours.":".$randomMins.":00";
        
        $rallyArray['active'] = 1;
        
        $randomSurfaces = array_rand($this->leagueSurfaces,rand(1,2));
        $randomSurfacePercentage = TK_Text::float_rand(10,90,2);
        $key = 0;
        if(is_array($randomSurfaces)){
            foreach($randomSurfaces as $key => $randomSurfaceId):
                $rallyArray['Surfaces'][$key]['surface'] = $this->leagueSurfaces[$randomSurfaceId];
                if($key == 0){
                    $rallyArray['Surfaces'][$key]['percentage'] = 100 - $randomSurfacePercentage;
                }
                else{
                    $rallyArray['Surfaces'][$key]['percentage'] = $randomSurfacePercentage;
                }
            endforeach;
        }
        else{
            $rallyArray['Surfaces'][$key]['surface'] = $this->leagueSurfaces[$randomSurfaces];
            $rallyArray['Surfaces'][$key]['percentage'] = 100;
        }
        $ifRain = rand(0,1);
        if($ifRain==1){
            $rallyArray['Surfaces'][$key+1]['surface'] = 'rain';
            $rallyArray['Surfaces'][$key+1]['percentage'] = TK_Text::float_rand(10,70,2);
        }
        
        $rallyArray['league_rally'] = 1;
        $rallyArray['league'] = $league;
        
        $rally = $this->rallyTable->getRecord();
        $rally->fromArray($rallyArray);
        
        $rally->save();
        for($i=0;$i<18;$i++){
            $this->createRandomStage($rally,'Stage '.$i,$i);
        }
        
        return $rally;
        
    }
    
    public function createRandomRally($league = false){
        
       $randomNumber = rand(1000000,10000000);
        
        $rallyArray = array();
        
        $rallyArray['name'] = "Rally ".$randomNumber;
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
            $this->createRandomStage($rally,'Stage '.$i,$i);
        }
        
        return $rally;
        
    }
    
    public function createRandomBigRally($league = false){
        
       $randomNumber = rand(1000000,10000000);
        
        $rallyArray = array();
        
        $rallyArray['name'] = "Rally ".$randomNumber;
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
            $this->createRandomStage($rally,'Stage '.$i,$i);
        }
        
        $this->getPrizesHelper()->createBigAward($rally['id'],'car','rand');
        $this->getPrizesHelper()->createBigAward($rally['id'],'premium','rand');
        $this->getPrizesHelper()->createBigAward($rally['id'],'premium','rand');
        
        return $rally;
        
    }
    
    public function deleteFriendlyRally($friendly){
        $rally = $friendly->get('Rally');
        $rally->get('Crews')->delete();
        $friendly->get('Participants')->delete();
        $friendly->get('Invitations')->delete();
        $rally->get('Surfaces')->delete();
        $rally->get('Stages')->delete();
        $rally->delete();
        $friendly->delete();
        
    }
    
    public function saveRallyStage($rally,$stage_name,$stage_length,$i){
        $stageArray = array();
        
        $stageArray['name'] = $stage_name;
        $stageArray['rally_id'] = $rally['id'];     
        $stageArray['length'] = $stage_length;
        
        // rally min time generator
        
        $timeMin = $stage_length / 95 * 60;
        $timeMax = $stage_length / 115 * 60;
        $timeMinUnix = strtotime((int)$timeMin." minutes");
        $timeMaxUnix = strtotime((int)$timeMax." minutes");
        $randomTimeUnix = mt_rand($timeMaxUnix,$timeMinUnix);
        $stageArray['min_time'] = date('H:i:s',$randomTimeUnix);
        
        $date = new DateTime($rally['date']);
        $date->add(new DateInterval('PT'.($i*15).'M'));
        $stageArray['date'] = $date->format('Y-m-d H:i:s');
        
        $stage = $this->stageTable->getRecord();
        $stage->fromArray($stageArray);
        
        $stage->save();
    }
    
    public function createRandomStage($rally,$stage_name,$i=false){
        $stageArray = array();
        
        $stageArray['name'] = $stage_name;
        $stageArray['rally_id'] = $rally['id'];     
        $stageArray['length'] = TK_Text::float_rand(2,30,2);
        
        
        
        // rally min time generator
        $timeMin = $stageArray['length'] / 95 * 60;
        $timeMax = $stageArray['length'] / 115 * 60;
        $timeMinUnix = strtotime((int)$timeMin." minutes");
        $timeMaxUnix = strtotime((int)$timeMax." minutes");
        $randomTimeUnix = mt_rand($timeMaxUnix,$timeMinUnix);
        $stageArray['min_time'] = date('H:i:s',$randomTimeUnix);
        
        $date = new DateTime($rally['date']);
        $date->add(new DateInterval('PT'.($i*15).'M'));
        $stageArray['date'] = $date->format('Y-m-d H:i:s');
        
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
    
    
    public function getRallyStage($rally,$name){
        $q = $this->stageTable->createQuery('s');
        $q->leftJoin('s.Rally r');
        $q->addWhere('r.id = ?',$rally['id']);
        $q->addWhere('LOWER(s.name) = ?',strtolower($name));
        return $q->fetchOne();
    }
    
    public function calculatePartialRallyResult(Rally_Model_Doctrine_Rally $rally){		
        $q = $this->stageResultTable->createQuery('sr');
        $q->leftJoin('sr.Stage s');
	$q->leftJoin('sr.Crew cr');
	$q->leftJoin('cr.Team t');
	$q->leftJoin('cr.Driver d');
	$q->leftJoin('cr.Pilot p');
        $q->select('SEC_TO_TIME(SUM(TIME_TO_SEC(sr.base_time))) as total_time');
        $q->addSelect('count(sr.id) as number_of_stages');
        $q->addSelect('SUM(sr.out_of_race) as out_of_race');
        $q->addSelect('sr.crew_id');
        $q->addSelect('stage_id');
        $q->addSelect('cr.*,d.*,p.*,t.*');
        $q->groupBy('crew_id');
        $q->addWhere('s.rally_id = ?',$rally['id']);
        $q->orderBy('out_of_race ASC,number_of_stages DESC,SUM(sr.out_of_race) ASC,total_time ASC');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
        
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
        $q->orderBy('out_of_race ASC,number_of_stages DESC,SUM(sr.out_of_race) ASC,total_time ASC');
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
                if(!$result['out_of_race']>0)
                {
                    if(!$rally['big_awards']){
                        $cashEarned = $this->getPrizesHelper()->calculatePrizeForPlace($result['position'],$leagueLevel,$participants);
                        $teamService->addTeamMoney($result['Crew']['team_id'],$cashEarned,1,'For '.TK_Text::addOrdinalNumberSuffix($result['position']).' place in rally '.$rally['name']);
                        if($rally['league_rally']){
                            if($result['position']<=10){
                                $leagueService->addTeamPoints($result['Crew']['team_id'],$result['position']);
                            }
                        }
                        if($result['position']==1){
                            $this->addAward($rally['id'], $result['Crew']['team_id']);
                        }
                    }
                    else{
                        $this->getPrizesHelper()->handleBigAwardForPlace($result['position'],$result['Crew']['team_id'],$rally,$bigAwardsCount);
                        
                        if($result['position']==1){
                            $this->addAward($rally['id'], $result['Crew']['team_id'],'big');
                        }
                    }
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
    
    public function getRallyStagesResults($id){
        $q = $this->stageTable->createQuery('s');
        $q->addSelect('s.*,sr.*,cr.*,d.*,p.*,t.*,a.*');
        $q->leftJoin('s.Results sr');
        $q->leftJoin('sr.Crew cr');
        $q->leftJoin('cr.Driver d');
        $q->leftJoin('cr.Pilot p');
        $q->leftJoin('cr.Team t');
        $q->leftJoin('sr.Accident a');
        $q->addWhere('s.rally_id = ?',$id);
        $q->orderBy('s.id,sr.out_of_race, sr.base_time');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function getNextBigRallyWithAwards(){
        $q = $this->rallyTable->createQuery('r');
        $q->addSelect('r.*,a.*,c.*');
        $q->leftJoin('r.BigAwards a');
        $q->leftJoin('a.Car c');
        $q->addWhere('r.big_awards = 1');
        $q->addWhere('r.date > NOW()');
        $q->orderBy('(CASE WHEN a.car_model_id IS NOT NULL THEN 0 ELSE 1 END),a.id ASC');
        $q->limit(1);
        return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
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
    
    public function countRecentUserFriendlies($user_id){
        $q = $this->friendlyTable->createQuery('f');
        $q->select('count(f.id) as cnt,f.created_at');
        $q->addWhere('f.user_id = ?',$user_id);
        
        $q->addWhere('f.from_gold_member = 1');
//        $q->addWhere('f.created_at > DATE_SUB(NOW(), INTERVAL +1 MONTH)');
        $q->groupBy('f.user_id');
        $q->orderBy('created_at');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function countRecentUserParticipateFriendlies($user_id){
        $q = $this->friendlyTable->createQuery('f');
        $q->leftJoin('f.Rally r');
        $q->leftJoin('f.Participants p');
        $q->select('count(f.id) as cnt,f.created_at');
        $q->addWhere('p.user_id = ?',$user_id);
        $q->addWhere('p.from_gold_member = 1');
        $q->addWhere('r.date > DATE_SUB(NOW(), INTERVAL +1 MONTH)');
        $q->groupBy('p.user_id');
        $q->orderBy('created_at');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function getAllFutureFriendlyRallies($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Friendly f');
        $q->addSelect('r.*,f.*');
	$q->addWhere('r.date > NOW()');
	$q->addWhere('r.friendly = 1');
        $q->addWhere('r.finished = 0');
        $q->orderBy('r.date');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getMyFriendlyRallies($user,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Friendly f');
        $q->leftJoin('r.Crews c');
        $q->select('r.*,f.*');
	$q->addWhere('r.friendly = 1');
        $q->addWhere('c.team_id = ? or f.user_id = ?',array($user['Team']['id'],$user['id']));
        $q->orderBy('r.date DESC');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getAllFutureTeamFriendlyRallies($user,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Friendly f');
        $q->leftJoin('r.Crews c');
        $q->select('r.id');
	$q->addWhere('r.date > NOW()');
	$q->addWhere('r.friendly = 1');
        $q->addWhere('r.finished = 0');
        $q->addWhere('c.team_id = ? or f.user_id = ?',array($user['Team']['id'],$user['id']));
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getFriendlyRally($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyTable->createQuery('f');
        $q->leftJoin('f.Rally r');
        $q->leftJoin('f.Participants p');
        $q->leftJoin('p.User u');
        $q->leftJoin('r.Crews c');
        $q->leftJoin('c.Driver d');
        $q->leftJoin('c.Pilot pi');
        $q->leftJoin('c.Car ca');
        $q->leftJoin('c.Team t');
        $q->addWhere($field .' = ?',$id);
        $q->select('r.*,f.*,p.*,u.*,c.*,ca.*,t.*,pi.*,d.*');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getFullFriendlyRally($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyTable->createQuery('f');
        $q->leftJoin('f.Rally r');
        $q->leftJoin('r.Stages s');
        $q->leftJoin('f.Participants p');
        $q->leftJoin('f.Invitations i');
        $q->leftJoin('p.User u');
        $q->leftJoin('f.User fu');
        $q->leftJoin('r.Crews c');
        $q->leftJoin('c.Driver d');
        $q->leftJoin('c.Pilot pi');
        $q->leftJoin('c.Car ca');
        $q->leftJoin('c.Team t');
        $q->leftJoin('t.User tu');
        $q->leftJoin('i.User iu');
        $q->leftJoin('iu.Team it');
        $q->leftJoin('r.Surfaces sf');
        $q->addWhere($field .' = ?',$id);
        $q->orderBy('sf.percentage DESC');
//        $q->select('*');
//        $q->select('r.*,f.*,p.*,u.*,c.*,ca.*,t.*,pi.*,d.*,it.*,i.*,iu.*,s.*,sf.*,fu.username','tu.*');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getFriendliesNotFinished($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendlyTable->createQuery('f');
        $q->leftJoin('f.Rally r');
        $q->addWhere('r.date < NOW()');
        $q->addWhere('r.finished = 0');
        return $q->execute(array(),$hydrationMode);
    }
    
    public function saveFriendlyRally($values,$user_id){
        $rally = $this->createRandomFriendlyRally($values);
        $friendly = $this->friendlyTable->getRecord();
        $friendly->fromArray($values);
        $friendly->rally_id = $rally['id'];
        $friendly->user_id = $user_id;
        $friendly->save();
        
        return $friendly;
    }
    
    public function createRandomFriendly($user_id){
        
       $randomNumber = rand(1000000,10000000);
       
        $values = array();
        $values['date'] = date('d-m-Y');
        $values['name'] = "Friendly_".$randomNumber;
        $rally = $this->createRandomFriendlyRally($values);
        $friendly = $this->friendlyTable->getRecord();
        $friendly->fromArray($values);
        $friendly->rally_id = $rally['id'];
        $friendly->user_id = $user_id;
        $friendly->save();
        
        return $friendly;
        
    }
    
     public function createRandomFriendlyRally($values){
        $rallyArray = array();
        
        $rallyArray['date'] = TK_Text::timeFormat($values['date']." 14:00",'Y-m-d H:i:s','d-m-Y H:i');
        $rallyArray['name'] = $values['name'];
        $rallyArray['slug'] = TK_Text::createUniqueTableSlug('Rally_Model_Doctrine_Rally',$values['name']);  
        $rallyArray['active'] = 1;
        $rallyArray['friendly'] = 1;
        $rallyArray['league'] = 3;
        
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
            $this->createRandomStage($rally,"Stage ".($i+1),$i);
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
    
    public function hasFriendlyInvitation($user_id){
        $q = $this->friendlyInvitationsTable->createQuery('fi');
        $q->addWhere('fi.user_id = ?',$user_id);
        $q->select('fi.id');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public function getMyFriendlyInvitations($user_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->rallyTable->createQuery('r');
        $q->leftJoin('r.Friendly f');
        $q->leftJoin('f.Invitations fi');
        $q->leftJoin('fi.User u');
        $q->addWhere('r.date > NOW()');
        $q->addWhere('u.id = ?',$user_id);
        $q->select('r.id');
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
    
    public function saveCrewToFriendlyRally($friendly_id,$crew_id,$user_id,$from_gold_member = false){
        $invitation = $this->friendlyParticipantsTable->getRecord();
        $invitation->user_id = $user_id;
        $invitation->friendly_id = $friendly_id;
        $invitation->crew_id = $crew_id;
        if($from_gold_member){
            $invitation->from_gold_member = $from_gold_member;
        }
        $invitation->save();
        return $invitation;
    }
    
    public function removeFriendlyInvite($friendly_id,$username){
        $invite = $this->getFriendlyInvitedUser($friendly_id,$username);
        if($invite)
            $invite->delete();
    }
    
    public function getRallyParticipant($rally_id,$team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->crewTable->createQuery('c');
        $q->select('c.*');
        $q->addWhere('c.rally_id = ?',$rally_id);
        $q->addWhere('c.team_id = ?',$team_id);
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function hasLeagueRallies($league_id,$startDate,$finishDate){
        $q = $this->rallyTable->createQuery('r');
        $q->addSelect('r.*');
        $q->addWhere('r.league_rally = 1');
        $q->addWhere('r.league like ?',$league_id);
        $q->addWhere('r.date > ?',$startDate);
        $q->addWhere('r.date < ?',$finishDate);
//        var_dump($q->fetchOne());exit;
        
	return $q->fetchOneArray();
    }
    
    public function saveBigAwards($values){
        $record = $this->bigAwardsTable->getRecord();
        $record->fromArray($values);
        $record->save();
    }
    
    
    public function saveRallyFromData($dataRally,$season_start,$league = 1){
        
        $stages = $dataRally->get('Stages');
        $surfaces = $dataRally->get('Surfaces');
        
        $newId = ($league-1)*1000+$dataRally['id'];
        $dataArray = $dataRally->toArray();
        
        unset($dataArray['Stages']);
        unset($dataArray['Surfaces']);
        unset($dataArray['id']);
        $rallyDay = strtotime($season_start." + ".($dataArray['week']-1)." weeks  + ".($dataRally['day']-1)." days");
        
        $randomHours = rand(8,14);
        if($randomHours == 8 || $randomHours == 9){
            $randomHours = "0".$randomHours;
        }
        $randomMins = $this->minsArray[array_rand($this->minsArray)];
        $dataArray['date'] = date('Y-m-d',$rallyDay)." ".$randomHours.":".$randomMins.":00";
        $dataArray['league'] = $league;
        if($dataArray['league']!=1){
            $dataArray['slug'] = TK_Text::createSlug($dataArray['name']." - level ".$league);
        }
        
        $dataArray['level'] = $league;
        $dataArray['id'] = $newId;
        $rally = $this->rallyTable->getRecord();
        $rally->fromArray($dataArray);
        
        $rally->save();
        $rally->set('id',$newId);
        $rally->save();
        
        foreach($stages as $key => $stage):
            $stageArray = $stage->toArray();
            $newDate = strtotime($dataArray['date']." + ".($key*15)." minutes");
            
            unset($stageArray['id']);
            $stageArray['date'] = date('Y-m-d H:i:s',$newDate);
            $stageArray['finished'] = 0;
            $stageArray['rally_id'] = $newId;
            $stageRow = $this->stageTable->getRecord();
            $stageRow->fromArray($stageArray);
            $stageRow->save();
            
        endforeach;
        
        foreach($surfaces as $surface):
            $surfaceArray = $surface->toArray();
            unset($surfaceArray['id']);
            
            $surfaceRow = $this->surfaceTable->getRecord();
            $surfaceArray['rally_id'] = $newId;
            $surfaceRow->fromArray($surfaceArray);
            $surfaceRow->save();
        endforeach;
        
    }
    
    
    
    public function addAward($rally_id,$team_id,$type = 'normal'){
        $award = $this->awardTable->getRecord();
        
        $award->set('team_id',$team_id);
        $award->set('rally_id',$rally_id);
        $award->set('type',$type);
        $award->save();
        
        return $award;
    }
    
    
    public function getTeamAwards($team_id){
        $q = $this->awardTable->createQuery('a');
        $q->leftJoin('a.Rally r');
        $q->addWhere('a.team_id = ?',$team_id);
        $q->orderBy('a.created_at DESC');
        $q->select('a.*,r.name');
        
        return $q->fetchArray();
    }
}
?>
