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
    
    public function __construct($id,$name,$cards){
        $this->id = $id;
        $this->name = $name;
        foreach($cards as $cardRow){
            $this->cards[$cardRow['id']] = new Car($cardRow['car_model_id']);
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
    
    public function getCard($no){
        $keys = array_keys($this->cards);
        if(isset($this->cards[$keys[$no-1]]))
            return $this->cards[$keys[$no-1]];
        
        return false;
    }
    
}
