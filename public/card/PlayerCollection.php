<?php
require_once(__DIR__."/Player.php");
require_once(__DIR__."/Table.php");
/**
 * Description of TableCollection
 *
 * @author Tomasz
 */
class PlayerCollection {
    private $items = array();

    public function addPlayer($userid,$username,$cards) {
        $player = new Player($userid,$username,$cards);
        $this->items[$userid] = $player;
        return $player;
    }

    public function removePlayer($userid,$tables) {
        if(isset($this->items[$userid])){
            $player = $this->items[$userid];
            if($player->getTable()){
                $tables->removeTable($player->getTable());
            }
            unset($this->items[$userid]);
        }
    }
    
    public function getPlayer($key) {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
        return false;
    }
    
    public function getJoinedPlayers(){
        $joinedPlayers = " ";
        foreach($this->items as $player):
            $joinedPlayers .= $player->getUsername()."<br />";
        endforeach;
        
        return $joinedPlayers;
    }
}
