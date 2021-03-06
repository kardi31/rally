<?php

class TeamService extends Service{
    
    protected $teamTable;
    protected $financeTable;
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new TeamService();
       return self::$instance;
    }
    
    protected $driverSkills = array(
        'composure', 'speed','regularity','reflex','on_gravel' ,'on_tarmac','on_snow','in_rain','talent'
    );
       
    protected $pilotSkills = array(
        'composure','talent', 'dictate_rhytm','diction','inteligence','route_description'
    );
    
    protected $financeTypes = array(
        1 => 'Income from rallies',
        2 => 'Sponsor cash',
        3 => 'Transfer income',
        4 => 'Player salaries',
        5 => 'Cars upkeep',
        6 => 'Transfer expense',
        7 => 'Car purchase',
        8 => 'Other incomes',
        9 => 'Other expenses'
    );
    
    public function __construct(){
        $this->teamTable = parent::getTable('team','team');
        $this->financeTable = parent::getTable('team','finance');
    }
    
    public function getAllTeams(){
        return $this->teamTable->findAll();
    }
    
    public function getTeam($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->teamTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function saveTeamFromArray($values,$team_id=false){
        if(!($team_id &&$team = $this->getTeam($team_id))){
            $team = $this->teamTable->getRecord();
        }
        $team->fromArray($values);
        $team->save();
        return $team;
    }
    
    public function getTeamWithLeague($id,$season,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->teamTable->createQuery('t');
        $q->innerJoin('t.User u');
        $q->innerJoin('t.Seasons s');
        $q->where('t.id = ?',$id);
        $q->andWhere('s.season = ?',$season);
	return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function createRandomTeam($values,$user = null){
        if($user)
            $values['name'] = $user['username']." team";
        else
            $values['name'] = $this->generateRandomString();
        
        $record = $this->teamTable->getRecord();
        $record->fromArray($values);
        $record->save();
        
        return $record;
    }
    
    public function getAllTeamsResults($hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->teamTable->createQuery('t');
        $q->select('group_concat(r.position) as group_position, t.id');
        $q->leftJoin('t.Crews c');
        $q->leftJoin('c.Result r');
        $q->groupBy('t.id');
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getLastMonthRalliesGrouppedByWeeks(){
        
    }
    
    public function selectRandomTeams($quantity = null,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->teamTable->createQuery('t');
        $q->innerJoin('t.User u');
        $q->leftJoin('t.Players p');
        $q->leftJoin('t.Cars c');
        $q->orderBy('RAND()');
        if(!is_null($quantity)){
            $q->limit($quantity);
        }
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTopWorldList($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->teamTable->createQuery('t');
        $q->innerJoin('t.User u');
        $q->andWhere('t.this_week_rank IS NOT NULL');
        $q->orderBy('t.this_week_rank');
        $q->limit(100);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getTopCountryList($country,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->teamTable->createQuery('t');
        $q->innerJoin('t.User u');
        $q->andWhere('t.this_week_rank IS NOT NULL');
        $q->andWhere('u.country = ?',$country);
        $q->orderBy('t.this_week_rank');
        $q->limit(100);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function getActiveCountries($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->teamTable->createQuery('t');
        $q->innerJoin('t.User u');
        $q->select('t.id,u.country as code');
        $q->groupBy('u.country');
        $q->addWhere('u.country IS NOT NULL');
        $q->limit(100);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function addTeamMoney($team_id,$amount,$moneyType,$description = false){
	if($amount==0||empty($amount)){
	    return ;
	}
	
        $team = $this->getTeam($team_id);
	$newCash = (int)$team['cash'] + $amount;
	
	$team->set('cash',$newCash);
	$team->save();
        
        $this->saveTeamFinance($team_id,$amount,$moneyType,true,$description);
        
        return $team;
    }
    
    public function removeTeamMoney($team_id,$amount,$moneyType,$description = false){
	if($amount==0||empty($amount)){
	    return ;
	}
	
        $team = $this->getTeam($team_id);
	
	$newCash = (int)$team['cash'] - $amount;
	
	$team->set('cash',$newCash);
	$team->save();
        
        $this->saveTeamFinance($team_id,$amount,$moneyType,false,$description);
    }
    
    public function removePreviousTeamMoney($team_id,$amount,$description){
	
        $team = $this->getTeam($team_id);
	
	$newCash = (int)$team['cash'] - $amount;
	
	$team->set('cash',$newCash);
	$team->save();
        
        $this->removeTeamFinance($team_id,$amount,$description);
    }
    
    public function removeTeamFinance($team_id,$amount,$description){        
        $q = $this->financeTable->createQuery('f');
        $q->addWhere('f.team_id = ?',$team_id);
        $q->addWhere('f.amount = ?',$amount);
        $q->addWhere('f.description = ?',$description);
        $q->orderBy('id DESC');
        $result = $q->fetchOne();
        $result->delete();
    }
    
    
    public function saveTeamFinance($team_id,$amount,$detailed_type,$income = false,$description = null){
        $financeArray = array();
        $financeArray['team_id'] = $team_id;
        $financeArray['amount'] = $amount;
        $financeArray['detailed_type'] = $detailed_type;
        if(!empty($description)){
            $financeArray['description'] = $description;
        }
        $financeArray['income'] = $income;
        
        $financeArray['save_date'] = date('Y-m-d H:i:s'); 
        
        $record = $this->financeTable->getRecord();
        $record->fromArray($financeArray);
        $record->save();
        
        return $record;
    }
    
    public function getSimpleReport($team_id,$prevWeek = false){
        if(!$prevWeek){
           $dateFrom = date('Y-m-d H:i:s', strtotime('this week monday'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next week monday'));
        }
        else{
           $dateFrom = date('Y-m-d H:i:s', strtotime('this week monday - '.$prevWeek.' week'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next week monday - '.$prevWeek.' week'));
        }
        
        
        $q = $this->financeTable->createQuery('f');
        $q->addWhere('save_date < ?',$dateTo);
        $q->addWhere('save_date > ?',$dateFrom);
        $q->addWhere('team_id = ?',$team_id);
        $q->groupBy('detailed_type');
        $q->orderBy('income,detailed_type');
        $q->select('sum(amount) as amount,detailed_type,income');
        $result = $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
        $sortedResult = $this->sortSimpleReport($result);
        return $sortedResult;
    }
    
    public function sortSimpleReport($results){
        $sortedResult = array('income' => array(),'expense' => array());
        foreach($results as $result):
            if($result['income']){
                $sortedResult['income'][] = $result;
            }
            else{
                $sortedResult['expense'][] = $result;
            }
        endforeach;
        
        return $sortedResult;
    }
    
    
    
    public function getFinanceTypes(){
        return $this->financeTypes;
    }
    
    public function getAdvancedReport($team_id,$prevWeek = false){
        if(!$prevWeek){
           $dateFrom = date('Y-m-d H:i:s', strtotime('previous monday - 1 week'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next monday'));
        }
        else{
           $dateFrom = date('Y-m-d H:i:s', strtotime('previous week monday - '.$prevWeek.' week'));
           $dateTo = date('Y-m-d H:i:s',strtotime('next monday - '.$prevWeek.' week'));
        }
        
        $q = $this->financeTable->createQuery('f');
        $q->addWhere('save_date < ?',$dateTo);
        $q->addWhere('save_date > ?',$dateFrom);
        $q->addWhere('team_id = ?',$team_id);
        $q->orderBy('save_date DESC');
        $q->select('amount,DATE(save_date) as save_date,detailed_type,income,description');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    public function selectLeagueFullTeams($league_name,$current_season = true,$hydrationMode = Doctrine_Core::HYDRATE_RECORD,$limit = false){
        // get league from current season by default
        // otherwise use season that is passed as a param
	if($current_season)
            $season = LeagueService::getInstance()->getCurrentSeason();
        else
            $season = (int)$current_season;
        $q = $this->teamTable->createQuery('t');
        $q->leftJoin('t.Seasons s');
        $q->addSelect('t.*');
	$q->where('s.league_name = ?',$league_name);
        $q->addWhere('s.season = ?',$season);
        if($limit)
            $q->limit($limit);
	return $q->execute(array(),$hydrationMode);
    }
    
    public function canAfford($team,$price){
        // team must be an instance of Team_Model_Doctrine_Team
        if(is_numeric($team)){
            $team = $this->getTeam($team,'id');
        }
        if($team->cash>=$price)
            return true;
        else
            return false;
    }
    
    public function getAllTeamPlayersSalary($team){
        $salary = 0;
        foreach($team->get('Players') as $player):
            $salary += $player['salary'];
        endforeach;
        
        return $salary;
    }
    
    public function getAllTeamCarsUpkeep($team){
        $upkeep = 0;
        foreach($team->get('Cars') as $car):
            $upkeep += $car['upkeep'];
        endforeach;
        
        return $upkeep;
    }
}
?>
