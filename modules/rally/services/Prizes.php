<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 class PrizesService extends Service{
     
    /*
     * prizes percentage for rallies with more than (or equal) 10 participants
     */
    protected $prizesOver10 = array(
        1 => 0.33,
        2 => 0.23,
        3 => 0.14,
        4 => 0.08,
        5 => 0.07,
        6 => 0.06,
        7 => 0.05,
        8 => 0.04
    );

    protected $points = array(
        1 => 20,
        2 => 14,
        3 => 10,
        4 => 7,
        5 => 6,
        6 => 5,
        7 => 4,
        8 => 3,
        9 => 2,
        10 => 1
    );
    
    /*
     * prizes percentage for rallies with less than 10 participants
     */

    protected $prizesUnder10 = array(
        1 => 0.25,
        2 => 0.18,
        3 => 0.12
    );
     
    protected $leaguePrizePool = array(
        1 => 140000,
        2 => 100000,
        3 => 75000,
        4 => 63000,
        5 => 50000
    );
    
    protected $leaguePerParticipantPool = array(
        1 => 2000,
        2 => 1500,
        3 => 1000,
        4 => 750,
        5 => 500
    );
            
    
    protected $bigAwardsTable;
    
     public function __construct(){
        $this->bigAwardsTable = parent::getTable('rally','bigAwards');
    }
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new self();
       return self::$instance;
    }
     
    public function getPrizes($league,$participants = 0){
        $prizePool = $this->leaguePrizePool[$league];
        $prizePerParticipant = $this->leaguePerParticipantPool[$league];
        $totalPrizePerParticipant = $prizePerParticipant * $participants;
        
        $prizePool += $totalPrizePerParticipant;
        
        if($participants>=10){
            $prizes = array_map( function($val) use ($prizePool) { return $val * $prizePool; }, $this->prizesOver10);
        }
        else{
            $prizes = array_map( function($val) use ($prizePool) { return $val * $prizePool; }, $this->prizesUnder10);
        }
        
        
        return $prizes;
    }
    
    public function getBigPrizes($rally,$participants = 0){
//        $prizePool = $this->leaguePrizePool[$league];
//        $prizePerParticipant = $this->leaguePerParticipantPool[$league];
//        $totalPrizePerParticipant = $prizePerParticipant * $participants;
//        
//        $prizePool += $totalPrizePerParticipant;
        $prizes = array();
        $key = 0;
        foreach($rally['BigAwards'] as $bigAward):
            if($bigAward['award_type']=="car"){
                $prizes[$key]['value'] = $bigAward['Car']->toArray();
            }
            else{
                $prizes[$key]['value'] = $bigAward['premium'];
            }
            
            $prizes[$key]['type'] = $bigAward['award_type'];
            $key++;
        endforeach;
        
//        if($participants>=10){
//            $prizes = array_map( function($val) use ($prizePool) { return $val * $prizePool; }, $this->prizesOver10);
//        }
//        else{
//            $prizes = array_map( function($val) use ($prizePool) { return $val * $prizePool; }, $this->prizesUnder10);
//        }
        
        
        return $prizes;
    }
    
    public function calculatePrizeForPlace($place,$league,$participants){
	
	if($place > 8){
	    return 0;
	}
	
        $prizePool = $this->leaguePrizePool[$league];
        $prizePerParticipant = $this->leaguePerParticipantPool[$league];
        $totalPrizePerParticipant = $prizePerParticipant * $participants;
        
        $prizePool += $totalPrizePerParticipant;
        
        if($participants>=10){
            $cash = round($this->prizesOver10[$place] * $prizePool);
        }
        else{
	    if($place > 3){
		return 0;
	    }
            $cash = round($this->prizesUnder10[$place] * $prizePool);
        }
        
        
        return $cash;
    }
    
    public function calculatePointsForPlace($place){
	if($place > 10){
	    return 0;
	}
	
        return $this->points[$place];
    }
    
    public function getPrizePool($league,$participants = 0){
        $prizePool = $this->leaguePrizePool[$league];
        $prizePerParticipant = $this->leaguePerParticipantPool[$league];
        $totalPrizePerParticipant = $prizePerParticipant * $participants;
        
        $prizePool += $totalPrizePerParticipant;
        
        
        
        
        return $prizePool;
    }
    
    public function handleBigAwardForPlace($position,$team_id,$rally,$bigAwardsCount){
        if($position>$bigAwardsCount)
            return null;
        
        $prize = $rally['BigAwards'][$position-1];
        if($prize['award_type']=='car'){
            CarService::getInstance()->createNewTeamCar($prize['car_model_id'],$team_id);
        }
        elseif($prize['award_type']=='premium'){
            $team = TeamService::getInstance()->getTeam($team_id);
            UserService::getInstance()->addPremium($team['user_id'],$prize['premium'],'Won in rally '.$rally['name']);
        }
        
    }
    
    
    
    public function createBigAward($rally_id,$type,$quantity_or_id){
        $bigAward = $this->bigAwardsTable->getRecord();
        $data = array();
        if($quantity_or_id=="rand"){
            if($type=="car"){
                $data['car_model_id'] = CarService::getInstance()->getRandomCarModel();
            }
            else{
                $data['premium'] = rand(300,5000);
            }
        }
        else{
            if($type=="car"){
                $data['car_model_id'] = $quantity_or_id;
            }
            else{
                $data['premium'] = $quantity_or_id;
            }
        }
        
        $data['award_type'] = $type;
        $data['rally_id'] = $rally_id;
        
        $bigAward->fromArray($data);
        $bigAward->save();
        
        return $bigAward;
    }
}