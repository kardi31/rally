<?php
require_once(__DIR__."/Car.php");
require_once(__DIR__."/Db.php");
/**
 * Description of Player
 *
 * @author Tomasz
 */
class Player {
    protected $db;
    protected $id;
    protected $name;
    protected $table;
    protected $cards;
    protected $rank;
    protected $notDoneCards;
    protected $point = 0;
    protected $timer = '2:00';
    
    // we must wait until animation is finished to add a point to user
    protected $pointToAdd = false;
    
    public function __construct($id,$name){
        $this->id = $id;
        $this->name = $name;
        $this->db = new Db();
        
        $this->setPlayerCards();
        $this->setPlayerRank();
    }
    
    public function getTimer($miliseconds = false){
        if($miliseconds){
            $explode = explode(':',$this->timer);
            $mins = (int)$explode[0];
            $secs = (int)$explode[1];
            return ($mins*60+$secs)*1000;
        }
        return $this->timer;
    }
    
    public function getMilisecondsFromTime($time){
        $explode = explode(':',$time);
        $mins = (int)$explode[0];
        $secs = (int)$explode[1];
        return ($mins*60+$secs)*1000;
    } 
    
    public function getId(){
        return $this->id;
    }
    
    public function getUsername(){
        return $this->name;
    }
    
    public function setTable($tableId){
        $this->table = $tableId;
    }
    
    
    public function setTimer($timer){
        $this->timer = $timer;
    }
    
    public function getTable(){
        if(isset($this->table))
            return $this->table;
        return false;
    }
    
    public function removeTable(){
        unset($this->table);
    }
    
    public function addPoint(){
        $this->point++;
    }
    
    
    public function addPointToAdd(){
        $this->pointToAdd = 1;
    }
    
    public function getPointToAdd(){
        return $this->pointToAdd;
    }
    
    public function getPoints(){
        return $this->point;
    }
    
    public function setPoints($points){
        $this->point = $points;
    }
    
    public function refreshPoints(){
        if($this->pointToAdd){
            $this->addPoint();
            $this->pointToAdd = false;
        }
    }
    
    public function resetPlayerInfo(){
        $this->pointToAdd = false;
        $this->point = 0;
        
        $this->timer = '2:00';
    }
    
    public function getCard($orderNo){
        $keys = array_keys($this->cards);
        if(isset($keys[$orderNo-1])&&isset($this->cards[$keys[$orderNo-1]]))
            return $this->cards[$keys[$orderNo-1]];
        
        return false;
    }
    
    public function getCardNotDone($orderNo){
//        echo $orderNo."\r\n";
        $keys = array_keys($this->notDoneCards);
        if(isset($this->notDoneCards[$keys[$orderNo-1]])){
//            var_dump($this->notDoneCards[$keys[$orderNo-1]]);
            return $this->notDoneCards[$keys[$orderNo-1]];
        }
        return false;
    }
    
    public function getCardById($id){
        if(isset($this->cards[$id]))
            return $this->cards[$id];
        
        return false;
    }
    
    public function setCardDone($id){
        $card = $this->getCardById($id);
        $card->setCardDone();
        unset($this->notDoneCards[$id]);
    }
    
    
    public function getCardPlace($card){
        $key = array_search($card,$this->cards);
        return $key;
    }
    
    public function openCard($cardId,$skillNo){        
        $card = $this->getCardById($cardId);
        if($card){
            $card->setOpened($skillNo);
        }
        return $card;
        
    }
    
    public function hasWon(){
        if($this->point>0){
            return true;
        }
    }
    
    public function moveLostCard($wonUserId){
        
//        $getWonCard = $this->db->fetch('select * from card_card where user_id = '.$this->id.' order by rand() limit 1');
        $getWonCard = $this->db->fetch('select * from card_card where user_id = '.$this->id.' order by id limit 1');
        $row = $this->db->execute('update card_card set user_id = '.$wonUserId.' where id = '.$getWonCard['id'].' limit 1');
        
        $wonCard = new Car($getWonCard['car_model_id'],$getWonCard['id']);
        
        return $wonCard;
    }
    
    public function setPlayerCards(){
        $playerCards = $this->db->fetchAll('select * from card_card where user_id = '.$this->id.' and locked = 0 order by id limit 5');
        
        unset($this->cards);
        unset($this->notDoneCards);
        
        foreach($playerCards as $cardRow){
            $car = new Car($cardRow['car_model_id'],$cardRow['id']);
            $this->cards[$cardRow['id']] = $car;
            $this->notDoneCards[$cardRow['id']] = $car;
        }
                
        if(count($playerCards)<5){
            return false;
        }
    }
    
    public function setPlayerRank(){
        $rank = $this->db->fetch('select card_rank from user_user where id = '.$this->id);
        if($rank){
            $this->setRank($rank['card_rank']);
        }
        
    }
    
    public function setRank($rank){
        $this->rank = $rank;
    }
    
    public function updateRank($rank){
        $this->setRank($rank);
        $this->db->execute('update user_user set card_rank = '.$rank.' where id = '.$this->id.' limit 1');
    }
    
    public function getRank(){
        return $this->rank;
    }
    
    public function hasEnoughCards(){
        if(count($this->cards)<5){
            return false;
        }
        return true;
    }
}
