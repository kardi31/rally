<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 class Rally_Helper_Prizes{
     
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
        1 => 200000,
        2 => 150000,
        3 => 100000,
        4 => 75000,
        5 => 50000
    );
    
    protected $leaguePerParticipantPool = array(
        1 => 2000,
        2 => 1500,
        3 => 1000,
        4 => 750,
        5 => 500
    );
            
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
}