<?php
require_once(__DIR__."/Car.php");
/**
 * Description of Player
 *
 * @author Tomasz
 */
class Player {
    protected $id;
    protected $name;
    protected $table;
    protected $cards;
    protected $notDoneCards;
    protected $point = 0;
    
    // we must wait until animation is finished to add a point to user
    protected $pointToAdd = false;
    
    public function __construct($id,$name,$cards){
        $this->id = $id;
        $this->name = $name;
        foreach($cards as $cardRow){
            $car = new Car($cardRow['car_model_id'],$cardRow['id']);
            $this->cards[$cardRow['id']] = $car;
            $this->notDoneCards[$cardRow['id']] = $car;
        }
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
    
    public function refreshPoints(){
        if($this->pointToAdd){
            $this->addPoint();
            $this->pointToAdd = false;
        }
    }
    
    public function getCard($orderNo){
        $keys = array_keys($this->cards);
        if(isset($this->cards[$keys[$orderNo-1]]))
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
        if($this->point>2){
            return true;
        }
    }
    
}
