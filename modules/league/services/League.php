<?php

class LeagueService extends Service{
    
    protected $leagueTable;
    protected $seasonTable;
    protected $seasonInfoTable;
    
    protected $maxTeamsInLeague = 16;
    protected $season = 1;
    
    private static $leagues = array(
        '1.0' => 1,
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
        '4.27' => 4,
        '5.1' => 5,
        '5.2' => 5,
        '5.3' => 5,
        '5.4' => 5,
        '5.5' => 5,
        '5.6' => 5,
        '5.7' => 5,
        '5.8' => 5,
        '5.9' => 5,
        '5.10' => 5,
        '5.11' => 5,
        '5.12' => 5,
        '5.13' => 5,
        '5.14' => 5,
        '5.15' => 5,
        '5.16' => 5
        
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
        $q->addWhere('s.season = ?',$this->season);
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
	
        
        return $league;
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
        $q->addWhere('s.season = ?',$this->season);
	$full_league_list = $q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        // if there's just one league
        if(!is_array($full_league_list)||empty($full_league_list)){
            if(!strlen($full_league_list)){
                reset(self::$leagues);
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
//        var_dump($league['league_name']);exit;
            $hasRallies = RallyService::getInstance()->hasLeagueRallies($league['league_name'],$seasonInfo['season_start'],$seasonInfo['season_finish']);
        $weeks = TK_Text::datediff('ww', $seasonInfo['season_start'],$seasonInfo['season_finish'], false);
        if(!$hasRallies){
            for($i=1;$i<=$weeks;$i++):
                RallyService::getInstance()->createOneLeagueRally($league['league_name'],$i,$seasonInfo['season_start']);
            endfor;
        }
        
        
    }
    
    public function getSeasonInfo(){
        $season = $this->getCurrentSeason();
        $q = $this->seasonInfoTable->createQuery('s');
        $q->addWhere('s.season = ?',$season);
        return $q->fetchOneArray();
    }
    
    
    
    public function selectTeamsForPromotion($current_season = true,$hydrationMode = Doctrine_Core::HYDRATE_RECORD,$limit = false){
        // get league from current season by default
        // otherwise use season that is passed as a param
	if(is_bool($current_season))
            $season = $this->season;
        else
            $season = (int)$current_season;
        $teamStatusArray = array();
        foreach(self::$leagues as $league => $league_level):
            $q = $this->seasonTable->createQuery('s');
            $q->leftJoin('s.Team t');
            $q->leftJoin('t.User u');
            $q->select('s.*,t.id,u.created_at,u.last_active');
            $q->where('s.league_name = ?',$league);
            $q->addWhere('s.season = ?',$season);
            $q->orderBy('s.points DESC');
            $result = $q->fetchArray();
            $teamNo = sizeOf($result);
            
            if($teamNo==0)
                continue;
            
            foreach($result as $key=>$teamSeasonRow){
//                
//                    if($key<2){
                        // if user wasn't active during last 5 weeks
                        // add team to demoted
                        if($teamSeasonRow['Team']['User']['created_at']<date('Y-m-d H:i:s',strtotime('- 5 weeks'))
                                && $teamSeasonRow['Team']['User']['last_active'] < date('Y-m-d H:i:s',strtotime('- 5 weeks'))
                                )
                        {
                            $teamStatusArray[$league_level]['demoted'][$teamSeasonRow['team_id']] = 'inactive';
                            continue;
                        }
                        // if top 2 position and league is not first - promote
                        // aposition because when sorting later we need to put those teams
                        // above inactive teams
                        if($league_level!=1&&$key<2){
                            $teamStatusArray[$league_level]['promoted'][$teamSeasonRow['team_id']] = "aposition";
                            continue;
                        }
                        
                        // elseif bottom 6 position - demote
                        elseif($key>=$teamNo-6){
                            $teamStatusArray[$league_level]['demoted'][$teamSeasonRow['team_id']] = 'aposition';
                            continue;
                        }
                        else{
                            // else if team not top 2 and not bottom 6
                            // put it into stay table
                            // array key = positions in league
                            $teamStatusArray[$league_level]['stay'][$teamSeasonRow['team_id']] = $key+1;
                        }
                        // else
                        // add team to promoted
//                        else{
//                            $teamStatusArray[$league_level]['promoted'][] = $teamSeasonRow['team_id'];
//                        }
//                    }
//                }
            }
        endforeach;
        $newLeaguesArray = array();
        
        $newLeaguesArray[1] = $teamStatusArray[1]['stay']+$teamStatusArray[2]['promoted'];
        
        $newLeaguesArray[2] = $teamStatusArray[1]['demoted']+$teamStatusArray[2]['stay']+$teamStatusArray[3]['promoted'];
        
        $newLeaguesArray[3] = $teamStatusArray[2]['demoted']+$teamStatusArray[3]['stay']+(array)$teamStatusArray[4]['promoted'];
        
        $newLeaguesArray[4] = (array)$teamStatusArray[3]['demoted']+(array)$teamStatusArray[4]['stay']+(array)$teamStatusArray[5]['promoted'];
//        Zend_Debug::dump($newLeaguesArray);exit;
        if(count($newLeaguesArray[1])<$this->maxTeamsInLeague){
            
            asort($newLeaguesArray[2],SORT_NATURAL);
            do{
                $k = key($newLeaguesArray[2]);
                $element = $newLeaguesArray[2][$k];
                $newLeaguesArray[1][$k] = $element;
                unset($newLeaguesArray[2][$k]);
            }
            while(count($newLeaguesArray[1])<$this->maxTeamsInLeague);
        }
        
        if(count($newLeaguesArray[2])<$this->maxTeamsInLeague*3 && isset($newLeaguesArray[3])){
            
            // if in league 2 not enough teams but in league 3 also not enough to fill league 2
            // do this to avoid stack in while loop
            if(count($newLeaguesArray[2])+count($newLeaguesArray[3])<$this->maxTeamsInLeague*3){
                $newLeaguesArray[2] = $newLeaguesArray[2]+(array)$newLeaguesArray[3];
                unset($newLeaguesArray[3]);
            }
            else{
                // sort to get teams with top position as first
                asort($newLeaguesArray[3],SORT_NATURAL);
                // promote teams with best position to higher league
                // until there's enough teams
                do{
                    $k = key($newLeaguesArray[3]);
                    $element = $newLeaguesArray[3][$k];
                    $newLeaguesArray[2][$k] = $element;
                    unset($newLeaguesArray[3][$k]);
                }
                while(count($newLeaguesArray[2])<$this->maxTeamsInLeague*3);
            }
        }
//        Zend_Debug::dump($newLeaguesArray[3]);exit;
        if(count($newLeaguesArray[3])<$this->maxTeamsInLeague*9 && isset($newLeaguesArray[4])){
            
            // if in league 2 not enough teams but in league 3 also not enough to fill league 2
            // do this to avoid stack in while loop
            if(count($newLeaguesArray[3])+count($newLeaguesArray[4])<$this->maxTeamsInLeague*9){
                $newLeaguesArray[3] = $newLeaguesArray[3]+(array)$newLeaguesArray[4];
                unset($newLeaguesArray[4]);
            }
            else{
                // sort to get teams with top position as first
                asort($newLeaguesArray[4],SORT_NATURAL);

                // promote teams with best position to higher league
                // until there's enough teams
                do{
                    $k = key($newLeaguesArray[4]);
                    $element = $newLeaguesArray[4][$k];
                    $newLeaguesArray[3][$k] = $element;
                    unset($newLeaguesArray[4][$k]);
                }
                while(count($newLeaguesArray[3])<$this->maxTeamsInLeague*9);
            }
        }
        
        return $newLeaguesArray;
    }
    
    public function promoteTeams($leagueTeamsArray){
        foreach($leagueTeamsArray as $league_level => $leagueTeam){
            foreach($leagueTeam as $team_id => $info){
                $league = $this->appendTeamToLeague($team_id);
                $team = TeamService::getInstance()->getTeam($team_id);
                $team->set('league_name',$league->get('league_name'));
                $team->save();
            }
        }
        Zend_Debug::dump($leagueTeamsArray);exit;
    }
}
?>
