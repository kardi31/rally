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

    public static $instance;
    
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=ral;charset=utf8', 'root', '');
    }
    
     public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function addPlayer($userid,$username) {
        if(!$player = new Player($userid,$username)){
            return false;
        }
        $this->items[$userid] = $player;
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
    
    public function getJoinedPlayers($domOutput = false){
        
        $dom = new DOMDocument();
            
        $joinedPlayers = $dom->createElement('ul');
        foreach($this->items as $player):
            $li = $dom->createElement('li',$player->getUsername());
            $spanRank = $dom->createElement('span',$player->getRank());
            $spanRank->setAttribute('class', 'rank');
            $span = $dom->createElement('span',($player->getTable()?'#'.$player->getTable():''));
            $span->setAttribute('class', 'tableId');
            
            $li->appendChild($span);
            $li->appendChild($spanRank);
            $joinedPlayers->appendChild($li);
        endforeach;
        
        if($domOutput)
            return $joinedPlayers;
        
        $dom->appendChild($joinedPlayers);
        
        return $dom->saveHTML();
    }
    
    public function getJoinedPlayersTable($domOutput = false){
        
        $dom = new DOMDocument();
            
        $joinedPlayers = $dom->createElement('ul');
        foreach($this->items as $player):
            $li = $dom->createElement('li',$player->getUsername());
            $spanRank = $dom->createElement('span',$player->getRank());
            $spanRank->setAttribute('class', 'rank');
            
            if(!$player->getTable()){
                $a = $dom->createElement('a','Invite to table');
                $a->setAttribute('class', 'inviteToTable');
                $a->setAttribute('data-rel', $player->getId());
            }
            else{
                $a = $dom->createElement('a','#'.$player->getTable());
                $a->setAttribute('class', 'alreadyOnTable');
            }
            
            $li->appendChild($a);
            $li->appendChild($spanRank);
            $joinedPlayers->appendChild($li);
        endforeach;
        
        if($domOutput)
            return $joinedPlayers;
        
        
        $dom->appendChild($joinedPlayers);
        
        return $dom->saveHTML();
    }
}
