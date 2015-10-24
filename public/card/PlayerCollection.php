<?php
require_once(__DIR__."/Player.php");
require_once(__DIR__."/Table.php");
/**
 * Description of TableCollection
 *
 * @author Tomasz
 */
class PlayerCollection {
    private $db ;
    private $items = array();

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=ral;charset=utf8', 'root', '');
    }
    
    public function addPlayer($userid,$username,$cards) {
        echo "addPlayer - ".count($this->items)." \r\n";
        $player = new Player($userid,$username,$cards);
        $this->items[$userid] = $player;
        echo "addPlayer - done - ".count($this->items)." \r\n";
        return $player;
    }

    public function removePlayer($userid,$tables) {
        if(isset($this->items[$userid])){
            $player = $this->items[$userid];
//            if($player->getTable()){
//                $tables->removeTable($player->getTable());
//            }
            unset($this->items[$userid]);
            if($player->getTable())
                return $player->getTable();
        }
        echo "removePlayer - ".count($this->items)." \r\n";
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
