<?php

class TeamService extends Service{
    
    protected $teamTable;
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
    
    
    public function __construct(){
        $this->teamTable = parent::getTable('team','team');
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
    
    public function addTeamMoney($team_id,$quantity,$moneyType){
	if($quantity==0||empty($quantity)){
	    return ;
	}
	
        $team = $this->getTeam($team_id);
	
	$newCash = (int)$team['cash'] + $quantity;
	
	$team->set('cash',$newCash);
	$team->save();
    }
    
}
?>
