<?php
require_once(__DIR__."/Player.php");
require_once(__DIR__."/Table.php");
/**
 * Description of TableCollection
 *
 * @author Tomasz
 */
class TableCollection {
    private $items = array();

    public function addTable(Player $player) {
        $nextTableId = $this->getNextEmptyTableId();
        $player->setTable($nextTableId);
        $table = new Table($player,$nextTableId);
        $this->items[$nextTableId] = $table;
        
        return $nextTableId;
    }
    
    public function addPlayerToTable(Player $player,Table $table) {
        $tableId = array_search($table,$this->items);
        $table->addPlayer($player);
        $player->setTable($tableId);
        
        return $tableId;
    }

    public function removeTable($key) {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }
    }

    public function getTable($key) {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
        return false;
    }
    
    private function getNextEmptyTableId(){
        if(count($this->items)==0){
            return 1;
        }
        for($i=1;$i<1000;$i++){
            if(!isset($this->items[$i])){
                return $i;
            }
        }
    }
    
    public function closeTable($player,$table){
        if($table->hasPlayer($player)){
            $tableId = $this->findTableId($table);
            $player->removeTable();
            if($table->hasBothPlayers()){
                $table->removePlayer($player);
            }
            else{
                $this->removeTable($tableId);
            }
        }
    }
    
    public function getAllTables(){
        $dom = new DOMDocument();
        ksort($this->items);
//        <div class="cardTableBox">
//            <div class="cardTableNo">Table 1</div>
//            <div class="cardTablePlayers">
//                <div class="cardTablePlayer">kk33</div>
//                <div class="cardTablePlayer">kardi3</div>
//            </div>
//            <button type="button" class="joinSingleTable btn btn-primary">Join table</button>
//        </div>
        
        foreach($this->items as $tableId => $table):
            
            $cardTableBox = $dom->createElement('div');
            $cardTableBox->setAttribute('class', 'cardTableBox');

            $cardTableNo = $dom->createElement('div','Table '.$tableId);
            $cardTableNo->setAttribute('class', 'cardTableNo');
            
            
            $cardTablePlayers = $dom->createElement('div');
            $cardTablePlayers->setAttribute('class', 'cardTablePlayers');
            
            $cardTableBox->appendChild($cardTableNo);
            $cardTableBox->appendChild($cardTablePlayers);
            
            $table->getPlayersInfo($dom,$cardTablePlayers);
            
            
            $joinSingleTable = $dom->createElement('button','Join table');
            $joinSingleTable->setAttribute('class', 'joinSingleTable btn btn-primary');
            $joinSingleTable->setAttribute('rel', $tableId);
            $cardTableBox->appendChild($joinSingleTable);
            
            $dom->appendChild($cardTableBox);
            
        endforeach;
        
        return $dom->saveHtml();
    }
    
    public function getJoinTableButton($tableId){
        $html = '<button type="button" class="joinSingleTable" rel="'.$tableId.'">Join table</button>';
        return $html;
    }
    
    public function findTableId($table){
        return array_search($table,$this->items);
    }
    
    
}
