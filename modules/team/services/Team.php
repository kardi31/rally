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
        3 => 'Player transfer income',
        4 => 'Player salaries',
        5 => 'Car costs',
        6 => 'Player transfer expense'
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
    
    public function createRandomTeam($values,$user_id = null){
        if($user_id)
            $values['name'] = "Team_".$user_id;
        else
            $values['name'] = $this->generateRandomString();
        
        $record = $this->teamTable->getRecord();
        $record->fromArray($values);
        $record->save();
        
        return $record;
    }
    
    public function selectRandomTeams($quantity,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->teamTable->createQuery('t');
        $q->where('t.driver1_id IS NOT NULL');
        $q->andWhere('t.pilot1_id IS NOT NULL');
        $q->andWhere('t.car1_id IS NOT NULL');
        $q->orderBy('RAND()');
        $q->limit($quantity);
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
        $q->orderBy('save_date DESC');
        $q->select('amount,DATE(save_date) as save_date,detailed_type,income,description');
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
}
?>
