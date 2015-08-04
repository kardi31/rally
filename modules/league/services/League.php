<?php

class LeagueService extends Service{
    
    protected $leagueTable;
    protected $seasonTable;
    protected $seasonInfoTable;
    
    protected $maxTeamsInLeague = 12;
    protected $season = 1;
    
    private static $leagues = array(
        '1' => 1,
        '2.1' => 2,
        '2.2' => 2,
        '2.3' => 2,
        '3.1' => 3,
        '3.2' => 3,
        '3.3' => 3,
        '3.4' => 3,
        '3.5' => 3,
        '3.6' => 3,
        '3.7' => 3,
        '3.8' => 3,
        '3.9' => 3,
        '4.1' => 4,
        '4.2' => 4,
        '4.3' => 4,
        '4.4' => 4,
        '4.5' => 4,
        '4.6' => 4,
        '4.7' => 4,
        '4.8' => 4,
        '4.9' => 4,
        '4.10' => 4,
        '4.11' => 4,
        '4.12' => 4,
        '4.13' => 4,
        '4.14' => 4,
        '4.15' => 4,
        '4.16' => 4,
        '4.17' => 4,
        '4.18' => 4,
        '4.19' => 4,
        '4.20' => 4,
        '4.21' => 4,
        '4.22' => 4,
        '4.23' => 4,
        '4.24' => 4,
        '4.25' => 4,
        '4.26' => 4,
        '4.27' => 4
    );
    
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new LeagueService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->leagueTable = parent::getTable('league','league');
        $this->seasonTable = parent::getTable('league','season');
        $this->seasonInfoTable = parent::getTable('league','seasonInfo');
    }
    
    public function getAllDrivers(){
        return $this->carTable->findAll();
    }
    
    
    public function getLeague($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->leagueTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function saveLeagueFromArray($data){
	
	$league = $this->leagueTable->getRecord();
	$league->fromArray($data);
	$league->save();
	
        return $league;
    }
    
    public function appendTeamToLeague($team_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
	
// check if there is any not full league
        $q = $this->seasonTable->createQuery('s');
	$q->groupBy('s.league_name');
	$q->having('count(s.league_name) < ?',$this->maxTeamsInLeague);
	$q->limit(1);
	
	$result = $q->fetchOne(array(),$hydrationMode);
	// if not, then get next free league
	if(!$result){
	    $league = $this->getNextEmptyLeague();
            
	}
	// if yes, use this league
	else{
	    $league = $result;
	}
        $this->checkLeagueRallies($league);
// save team to league
	$newTeamData = array(
	  'team_id' => $team_id,
	  'league_name' => $league->get('league_name'),
	  'season' => $this->season
	);
	$save_query = $this->seasonTable->getRecord();
	$save_query->fromArray($newTeamData);
	$save_query->save();
	
        return $save_query;
    }
    
    public function getRandomLeague(){
        $q = $this->seasonTable->createQuery('s');
	$q->select('s.league_name');
        $q->groupBy('s.league_name');
        // get leagues with teams in it
        $q->leftJoin('s.Team t');
        $q->addWhere('t.driver1_id IS NOT NULL');
        $q->orderBy('rand()');
	return $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    }
    
    public function getAllActiveLeagues(){
        $season = $this->getCurrentSeason();
        
        $q = $this->seasonTable->createQuery('s');
	$q->select('s.league_name');
        $q->groupBy('s.league_name');
        $q->addWhere('s.season = ?',$season);
	return $q->fetchArray();
    }
    
    public function getNextEmptyLeague($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->seasonTable->createQuery('s');
	$q->select('s.league_name');
        $q->groupBy('s.league_name');
	$full_league_list = $q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        // if there's just one league
        if(!is_array($full_league_list)||empty($full_league_list)){
            if(!strlen($full_league_list)){
                $leagueData['league_name'] = key(self::$leagues);
                $leagueData['league_level'] = (int)key(self::$leagues);
                if(!$league = $this->getLeague(key(self::$leagues),'league_name')){
                    $league = $this->saveLeagueFromArray($leagueData);
                }
                return $league;
                
            }
            
            $league_id = $full_league_list;
            $full_league_list = array();
            $full_league_list[] = $league_id;
        }
        
        $full_league_list_query = join(',',$full_league_list);
        
	$league_query = $this->leagueTable->createQuery('l');
        $league_query->select('*');
	$league_query->addWhere("l.league_name NOT IN ($full_league_list_query)");
	$league_query->orderBy('l.league_name');
	return $league_query->fetchOne(array(),$hydrationMode);
    }
    
    
    public function getCurrentSeason(){
        return $this->season;
    }
    
    public function getTeamLeague($team_id,$current_season = true,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        // get league from current season by default
        // otherwise use season that is passed as a param
	if($current_season)
            $season = $this->season;
        else
            $season = (int)$current_season;
        $q = $this->seasonTable->createQuery('s');
        $q->leftJoin('s.League l');
	$q->where('s.team_id = ?',$team_id);
        $q->addWhere('s.season = ?',$season);
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    
    public function getLeagueTable($league_name,$current_season = true,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        // get league from current season by default
        // otherwise use season that is passed as a param
	if($current_season)
            $season = $this->season;
        else
            $season = (int)$current_season;
        $q = $this->seasonTable->createQuery('s');
        $q->leftJoin('s.League l');
        $q->leftJoin('s.Team t');
	$q->where('s.league_name = ?',$league_name);
        $q->addWhere('s.season = ?',$season);
        $q->orderBy('s.points DESC,t.name');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function selectLeagueFullTeams($league_name,$current_season = true,$hydrationMode = Doctrine_Core::HYDRATE_RECORD,$limit = false){
        // get league from current season by default
        // otherwise use season that is passed as a param
	if($current_season)
            $season = $this->season;
        else
            $season = (int)$current_season;
        $q = $this->seasonTable->createQuery('s');
        $q->leftJoin('s.League l');
        $q->leftJoin('s.Team t');
        $q->select('s.league_name');
        $q->addSelect('t.*');
	$q->where('s.league_name = ?',$league_name);
        $q->addWhere('s.season = ?',$season);
        if($limit)
            $q->limit($limit);
	return $q->execute(array(),$hydrationMode);
    }
    
    
    public function addTeamPoints($team_id,$position){
        $rallyService = RallyService::getInstance();
        $season = $this->getCurrentSeason();
        
        $points = $rallyService->getPrizesHelper()->calculatePointsForPlace($position);
        
	$q = $this->seasonTable->createQuery('s');
        $q->addWhere('s.season = ?',$season);
        $q->addWhere('s.team_id = ?',$team_id);
        $teamSeasonTable = $q->fetchOne(array());
        
        $currentPoints = $teamSeasonTable->get('points');
        $newPoints = $currentPoints + $points;
        $teamSeasonTable->set('points',$newPoints);
        $teamSeasonTable->save();
    }
    
    public function checkLeagueRallies($league){
        $seasonInfo = $this->getSeasonInfo();
        
            $hasRallies = RallyService::getInstance()->hasLeagueRallies($league['league_name'],$seasonInfo['season_start'],$seasonInfo['season_finish']);
        
        $weeks = TK_Text::datediff('ww', date('Y-m-d H:i:s'),$seasonInfo['season_finish'], false);
       
        if(!$hasRallies){
            for($i=2;$i<=$weeks+1;$i++):
                RallyService::getInstance()->createOneLeagueRally($league['league_name'],$i);
            endfor;
        }
        
        
    }
    
    public function getSeasonInfo(){
        $season = $this->getCurrentSeason();
        $q = $this->seasonInfoTable->createQuery('s');
        $q->addWhere('s.season = ?',$season);
        return $q->fetchOneArray();
    }
}
?>
