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
        
/*    <div class="message_box" id="cardTable">
        <div class="player1Cards playerCards">
            <div id="player1card1">
                <table>
                    <tr>
                        <th colspan="2">
                            <img src="/media/cars/opel_viva.jpg" alt="Opel Viva GT" class="">
                            <strong>Suzuki Swift Sport</strong>
                        </th>
                    </tr>
                    <tr>
                        <td>Acceleration</td>
                        <td>3.5</td>
                    </tr>
                    <tr>
                        <td>v-max</td>
                        <td>210</td>
                    </tr>
                    <tr>
                        <td>Capacity</td>
                        <td>1598</td>
                    </tr>
                    <tr>
                        <td>Horsepower</td>
                        <td>208</td>
                    </tr>
                </table>
            </div>
            <div id="player1Card2">Card2</div>
            <div id="player1Card3">Card3</div>
            <div id="player1Card4">Card4</div>
            <div id="player1Card5">Card5</div>
        </div>
        <div class="playField"></div>
        <div class="player2Cards playerCards">
            <div id="player2Card1">Card1</div>
            <div id="player2Card2">Card2</div>
            <div id="player2Card3">Card3</div>
            <div id="player2Card4">Card4</div>
            <div id="player2Card5">Card5</div>
        </div>
    </div>
    <div class="message_box" id="cardTableInfo">
        
        <button type="button" class="closeTable" rel="1">X</button>
        <div class="playerList">
            <div>kk33</div>
            <div>kardi3</div>
        </div>
    </div>
 * 
 */
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
            $player1card = $dom->createElement('div');
            $player1card->setAttribute('id', 'player1card'.$i);
            
            if(isset($this->player1)&&$card = $this->player1->getCard($i)){
                
                $playerCardTable = $this->createPlayerCardTable($card,$dom);
                
                $player1card->appendChild($playerCardTable);
            }
            
            $player1Cards->appendChild($player1card);
            
            $player2card = $dom->createElement('div');
            $player2card->setAttribute('id', 'player2card'.$i);
            
            if(isset($this->player2)&&$card = $this->player2->getCard($i)){
                
                $playerCardTable = $this->createPlayerCardTable($card,$dom);
                
                $player2card->appendChild($playerCardTable);
            }
            
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
    
//     <table>
//                    <tr>
//                        <th colspan="2">
//                            <img src="/media/cars/opel_viva.jpg" alt="Opel Viva GT" class="">
//                            <strong>Suzuki Swift Sport</strong>
//                        </th>
//                    </tr>
//                    <tr>
//                        <td>Acceleration</td>
//                        <td>3.5</td>
//                    </tr>
//                    <tr>
//                        <td>v-max</td>
//                        <td>210</td>
//                    </tr>
//                    <tr>
//                        <td>Capacity</td>
//                        <td>1598</td>
//                    </tr>
//                    <tr>
//                        <td>Horsepower</td>
//                        <td>208</td>
//                    </tr>
//                </table>
    
    public function createPlayerCardTable($card,$dom){
        $table = $dom->createElement('table');
        
        // tr 1 - start
        
        $tr1 = $dom->createElement('tr');
        $th1 = $dom->createElement('th');
        $th1->setAttribute('colspan', 2);
        $img = $dom->createElement('img');
        $img->setAttribute('src', '/media/cars/'.$card->getPhoto());
        
        $strong = $dom->createElement('strong',$card->getName());
        $th1->appendChild($img);
        $th1->appendChild($strong);
        $tr1->appendChild($th1);
        
        // tr 2 - start
        
        $tr2 = $dom->createElement('tr');
        $td21 = $dom->createElement('td','Acceleration');
        $td22 = $dom->createElement('td',$card->getAcceleration());
        
        $tr2->appendChild($td21);
        $tr2->appendChild($td22);
        
        // tr 2 - start
        
        $tr3 = $dom->createElement('tr');
        $td31 = $dom->createElement('td','V-max');
        $td32 = $dom->createElement('td',$card->getMaxSpeed());
        
        $tr3->appendChild($td31);
        $tr3->appendChild($td32);
        
        // tr 2 - start
        
        $tr4 = $dom->createElement('tr');
        $td41 = $dom->createElement('td','Capacity');
        $td42 = $dom->createElement('td',$card->getCapacity());
        
        $tr4->appendChild($td41);
        $tr4->appendChild($td42);
        
        // tr 2 - start
        
        $tr5 = $dom->createElement('tr');
        $td51 = $dom->createElement('td','Horsepower');
        $td52 = $dom->createElement('td',$card->getHorsePower());
        
        $tr5->appendChild($td51);
        $tr5->appendChild($td52);
        
        $table->appendChild($tr1);
        $table->appendChild($tr2);
        $table->appendChild($tr3);
        $table->appendChild($tr4);
        $table->appendChild($tr5);
        
        return $table;
    }
}
