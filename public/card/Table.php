<?php

/**
 * Description of Table
 *
 * @author Tomasz
 */
class Table {
    
    protected $player1;
    protected $player2;
    protected $id;
    
    public function __construct($player,$id){
        $this->player1 = $player;
        $this->id = $id;
    }
    
    
    public function getPlayersInfo($dom,$cardTablePlayers){
        if(isset($this->player1)){
            $player1 = $dom->createElement('div',$this->player1->getUsername());
            $player1->setAttribute('class','cardTablePlayer');
            $cardTablePlayers->appendChild($player1);
        }
        
        if(isset($this->player2)){
            $player2 = $dom->createElement('div',$this->player2->getUsername());
            $player2->setAttribute('class','cardTablePlayer');
            $cardTablePlayers->appendChild($player2);
        }
    }
    
    public function addPlayer($player){
        $this->player2 = $player;
    }
    
    
    public function hasPlayer($player){
        if(isset($this->player2)&&$this->player2 == $player || isset($this->player1)&&$this->player1 == $player){
            return true;
        }
        return false;
    }
    
    public function hasBothPlayers(){
        if(isset($this->player2)&&isset($this->player1)){
            return true;
        }
        return false;
    }
    
    public function removePlayer($player){
        if($this->player1==$player){
            unset($this->player1);
        }
        elseif($this->player2==$player){
            unset($this->player2);
        }
    }
    
    public function showTable(){
        
//    <div class="message_box" id="cardTable">
//        <div class="player1Cards playerCards">
//            <div id="player1Card1">Card1</div>
//            <div id="player1Card2">Card2</div>
//            <div id="player1Card3">Card3</div>
//            <div id="player1Card4">Card4</div>
//            <div id="player1Card5">Card5</div>
//        </div>
//        <div class="playField"></div>
//        <div class="player2Cards playerCards">
//            <div id="player2Card1">Card1</div>
//            <div id="player2Card2">Card2</div>
//            <div id="player2Card3">Card3</div>
//            <div id="player2Card4">Card4</div>
//            <div id="player2Card5">Card5</div>
//        </div>
//    </div>
//    <div class="message_box" id="cardTableInfo">
//        
//        <button type="button" class="closeTable" rel="1">X</button>
//        <div class="playerList">
//            <div>kk33</div>
//            <div>kardi3</div>
//        </div>
//    </div>
        $dom = new DOMDocument();
            
        $cardTableWrapper = $dom->createElement('div');
        $cardTableWrapper->setAttribute('class', 'cardTableWrapper');

        $cardTable = $dom->createElement('div');
        $cardTable->setAttribute('id', 'cardTable');
        
        $player1Cards = $dom->createElement('div');
        $player1Cards->setAttribute('class', 'player1Cards playerCards');
        
        $player2Cards = $dom->createElement('div');
        $player2Cards->setAttribute('class', 'player2Cards playerCards');
        
        for($i=1;$i<=5;$i++){
            $player1card = $dom->createElement('div','Card'.$i);
            $player1card->setAttribute('id', 'player1card'.$i);
            $player1Cards->appendChild($player1card);
            
            $player2card = $dom->createElement('div','Card'.$i);
            $player2card->setAttribute('id', 'player2card'.$i);
            $player2Cards->appendChild($player2card);
        }
        
        $playField = $dom->createElement('div');
        $playField->setAttribute('class', 'playField');
        
        $cardTable->appendChild($player1Cards);
        $cardTable->appendChild($playField);
        $cardTable->appendChild($player2Cards);
        
        // card table finish
        
        // card table info start
        
        $cardTableInfo = $dom->createElement('div');
        $cardTableInfo->setAttribute('id', 'cardTableInfo');
        
        $tableId = $this->getId();
        
        $closeTable = $dom->createElement('button','X');
        $closeTable->setAttribute('class', 'closeTable');
        $closeTable->setAttribute('rel', $tableId);
        
        $playerList = $dom->createElement('div');
        $playerList->setAttribute('class', 'playerList');
        
        if(isset($this->player1)){
            $player1 = $dom->createElement('div',$this->player1->getUsername());
            $playerList->appendChild($player1);
        }
        
        if(isset($this->player2)){
            $player2 = $dom->createElement('div',$this->player2->getUsername());
            $playerList->appendChild($player2);
        }
        
        if(isset($tableId)){
            $tableIdBox = $dom->createElement('div','Table '.$tableId);
            $cardTableInfo->appendChild($tableIdBox);
        }
        
        $cardTableInfo->appendChild($closeTable);
        $cardTableInfo->appendChild($playerList);
        
        $dom->appendChild($cardTable);
        $dom->appendChild($cardTableInfo);
        
        return $dom->saveHTML();
        
    }
    
    public function getPlayerIds(){
        $playerIds = array();
        
        if(isset($this->player1)){
            $playerIds['user_id'] = $this->player1->getId();
        }
        
        if(isset($this->player2)){
            if(empty($playerIds)){
                $playerIds['user_id'] = $this->player2->getId();
            }
            else{
                $playerIds['user_id2'] = $this->player2->getId();
            }
        }
        return $playerIds;
    }
    
    public function getId(){
        return $this->id;
    }
}
