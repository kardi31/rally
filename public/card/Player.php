<?php
/**
 * Description of Player
 *
 * @author Tomasz
 */
class Player {
    protected $id;
    protected $name;
    protected $table;
    
    public function __construct($id,$name){
        $this->id = $id;
        $this->name = $name;
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
    
}
