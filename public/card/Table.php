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
    protected $moves = array(
        'player1' => array(),
        'player2' => array(),
        'won' => array()
    );
    protected $starting_player;
    protected $current_skill_playing;
    protected $skillOrder = array('acceleration','max_speed','capacity','horsepower');
    protected $round = 1;
    protected $next_move_first_player = false;
    
    public function __construct($player,$id){
        $this->player1 = $player;
        $this->id = $id;
    }
    
    private function isGameJustStarted(){
        
        if(isset($this->player2)&& isset($this->player1)){
            if(empty($this->moves['player1'])&&empty($this->moves['player2'])){
                return true;
            }
        }
        
        return false;
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
        $this->setStartingPlayer();
    }
    
    public function setStartingPlayer(){
//        $playerNo = rand(1,2);
        $playerNo = 1;
        $this->starting_player = 'player'.$playerNo;
    }
    
    // skill which one of players has chosen
    // and second player must respond to it using exactly the same skill
    public function setCurrentSkillPlaying($skillId){
        $this->current_skill_playing = $this->skillOrder[$skillId-1];
    }
    
    public function getCurrentSkillPlaying(){
        return $this->current_skill_playing;
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
    
    
    public function getSkill($skillId){
        return $this->skillOrder[$skillId-1];
    }
    
    public function makeAMove($player,$cardOrderNo,$skillId){
        
        // check first move
        
        if($this->isGameJustStarted()){
                echo "just started - 0 \r\n";
            if($player==$this->{$this->starting_player}){
                echo "just started - 1 \r\n";
                $card = $player->getCard($cardOrderNo);
                $this->moves[$this->starting_player][] = $card->getId();
                $this->setCurrentSkillPlaying($skillId);
                return true;
            }
        }
        elseif(isset($this->next_move_first_player)&&$this->next_move_first_player&&$player==$this->{$this->next_move_first_player}){
                echo "nextmove first player \r\n";
            $card = $player->getCard($cardOrderNo);
            $this->moves[$this->next_move_first_player][] = $card->getId();
            $this->setCurrentSkillPlaying($skillId);
            return true;
        }
        else{
                     echo "else \r\n";
            // check first move from 2nd player
            if(($this->round==1&&$this->starting_player == 'player1')||$this->next_move_first_player=='player1'){
                $secondPlayer = 'player2';
            }
            elseif(($this->round==1&&$this->starting_player == 'player2')||$this->next_move_first_player=='player2'){
                $secondPlayer = 'player1';
            }
            var_dump($secondPlayer);
            if($player==$this->{$secondPlayer}){
                
                
                     echo "else - in \r\n";
                $requestedSkill = $this->getSkill($skillId);
                if($this->current_skill_playing==$requestedSkill){
                    
                    // when both players chosen their cards
                    // open both cards at once
                    if($this->round==1){
                        $this->{$this->starting_player}->openCard($this->moves[$this->starting_player][$this->round-1],$skillId);
                    }
                    else{
                        $this->{$this->next_move_first_player}->openCard($this->moves[$this->next_move_first_player][$this->round-1],$skillId);
                    }
                    $card = $player->getCard($cardOrderNo);
                    $player->openCard($card->getId(),$skillId);
                    
                    
                    $this->moves[$secondPlayer][] = $card->getId();
                    $this->checkCardWon($this->round);
                }
            }
        }
        
        if(isset($this->starting_player)&&isset($secondPlayer)){
            $countPlayer1Moves = count($this->moves[$secondPlayer]);
            $countPlayer2Moves = count($this->moves[$this->starting_player]);

            if($countPlayer1Moves>0&&$countPlayer1Moves=$countPlayer2Moves){
                return true;
            }
        }
        
        if(isset($this->next_move_first_player)&&isset($secondPlayer)){
            if($this->next_move_first_player){
                $countPlayer1Moves = count($this->moves[$secondPlayer]);
                $countPlayer2Moves = count($this->moves[$this->next_move_first_player]);

                if($countPlayer1Moves>0&&$countPlayer1Moves=$countPlayer2Moves){
                    return true;
                }
            }
        }
        return false;
        
        
    }
    
    public function showTable($user_id = null,$refreshPoints = false){
        
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
        if($refreshPoints){
            $this->refreshPoints();
        }
        
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
            if(isset($this->player1)&&$card = $this->player1->getCard($i)){
                if(!$card->isDone()){
                    $player1card->setAttribute('id', 'player1card'.$i);
                    $player1card->setAttribute('data-rel', $i);
                    $player1card->setAttribute('class', 'playerCard');


                    if($user_id==$this->player1->getId()||$card->isOpened()){
                        $playerCardTable = $this->createPlayerCardTable($card,$dom,'player1');

                        if($card->isOpened()){
                            $imgDiv = $dom->createElement('div');
                            $cardImg = $dom->createElement('img');
                            $cardImg->setAttribute('src', '/images/card_back.png');

                            $tableDiv = $dom->createElement('div');
                            $imgDiv->appendChild($cardImg);
                            $tableDiv->appendChild($playerCardTable);
                            $player1card->appendChild($imgDiv);
                            $player1card->appendChild($tableDiv);

                            $player1card->setAttribute('class', 'playerCard flipIt');
                        }
                    }
                    else{
                        $playerCardTable = $dom->createElement('img');
                        $playerCardTable->setAttribute('src', '/images/card_back.png');
                    }
                    if(!$card->isOpened()){
                        $player1card->appendChild($playerCardTable);
                    }
                }
            }
            
            $player1Cards->appendChild($player1card);
            
            $player2card = $dom->createElement('div');
            
            
            if(isset($this->player2)&&$card = $this->player2->getCard($i)){
                
                if(!$card->isDone()){
                    $player2card->setAttribute('id', 'player2card'.$i);
                    $player2card->setAttribute('data-rel', $i);
                    $player2card->setAttribute('class', 'playerCard');


                    if($user_id==$this->player2->getId()||$card->isOpened()){
                        $playerCardTable = $this->createPlayerCardTable($card,$dom,'player2');

                        if($card->isOpened()){
                            $imgDiv = $dom->createElement('div');
                            $cardImg = $dom->createElement('img');
                            $cardImg->setAttribute('src', '/images/card_back.png');

                            $tableDiv = $dom->createElement('div');
                            $imgDiv->appendChild($cardImg);
                            $tableDiv->appendChild($playerCardTable);
                            $player2card->appendChild($imgDiv);
                            $player2card->appendChild($tableDiv);
                            $player2card->setAttribute('class', 'playerCard flipIt');
                        }
                    }
                    else{
                        $playerCardTable = $dom->createElement('img');
                        $playerCardTable->setAttribute('src', '/images/card_back.png');
                    }

                    if(!$card->isOpened()){
                        $player2card->appendChild($playerCardTable);
                    }
                }
            }
            
            $player2Cards->appendChild($player2card);
        }
        
        $playField = $dom->createElement('div');
        $playField->setAttribute('class', 'playField');
        
        if(!$this->isFinished()){
            if($this->isGameJustStarted()){
                $whoStartInfo = $dom->createElement('div',ucfirst($this->starting_player." starts the game"));
                $whoStartInfo->setAttribute('class', 'label label-danger');
                $playField->appendChild($whoStartInfo);
            }

            if($this->next_move_first_player){
                $whoStartInfo = $dom->createElement('div',ucfirst($this->next_move_first_player." do next move"));
                $whoStartInfo->setAttribute('class', 'label label-danger');
                $playField->appendChild($whoStartInfo);
            }
        }
        else{
            $whoWon = $dom->createElement('div',$this->isFinished());
            $whoWon->setAttribute('class', 'label label-danger');
            $playField->appendChild($whoWon);
        }
        
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
            $player1Cards->setAttribute('data-id', $this->player1->getId());
            $player1 = $dom->createElement('div',$this->player1->getUsername());
            
            $player1Points = $dom->createElement('div',$this->player1->getPoints());
            
            $player1->appendChild($player1Points);
            $playerList->appendChild($player1);
        }
        
        if(isset($this->player2)){
            $player2Cards->setAttribute('data-id', $this->player2->getId());
            $player2 = $dom->createElement('div',$this->player2->getUsername());
            
            
            $player2Points = $dom->createElement('div',$this->player2->getPoints());
            
            $player2->appendChild($player2Points);
            
            $playerList->appendChild($player2);
        }
        
        $cardTableInfo->appendChild($closeTable);
        if(isset($tableId)){
            $tableIdBox = $dom->createElement('div','Table '.$tableId);
            $tableIdBox->setAttribute('class', 'tableName');
            $cardTableInfo->appendChild($tableIdBox);
        }
        
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
    
    public function createPlayerCardTable($card,$dom,$whichPlayer){
        $table = $dom->createElement('table');
        
        // tr 1 - start
        
        $tr1 = $dom->createElement('tr');
        $th1 = $dom->createElement('th');
        if($card->isOpened()){
            $tr1->setAttribute('class','opened');
        }
        
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
        
        $currentPlayingSkillId = $this->findCurrentPlayingSkillId();
        if(is_int($currentPlayingSkillId)){ // &&$card->isOpened()
            // if it is a player who just clicked on card
            if(isset($this->moves[$whichPlayer][$this->round-1])){
                // get the card which was just clicked
                // and apply styles just to this card
                if($card->getId()==$this->moves[$whichPlayer][$this->round-1]){
                    ${"tr".($currentPlayingSkillId+2)}->setAttribute('class','currentPlayingSkill');
                    $table->setAttribute('class','selectedCard');
                }
            }
            // for player who not clicked anything
            // mark skills which need to be chosen
            else{
                ${"tr".($currentPlayingSkillId+2)}->setAttribute('class','currentPlayingSkill');
            }
        }
        
        $table->appendChild($tr1);
        $table->appendChild($tr2);
        $table->appendChild($tr3);
        $table->appendChild($tr4);
        $table->appendChild($tr5);
        
        return $table;
    }
    
    
    public function findCurrentPlayingSkillId(){
        if(!isset($this->current_skill_playing))
            return false;
        $key = array_search($this->current_skill_playing,$this->skillOrder);
        return $key;
    }
    
    public function checkCardWon(){
        $round = $this->round;
        $card1Id = $this->moves['player1'][$round-1];
        $card1 = $this->player1->getCardById($card1Id);
        
        $card2Id = $this->moves['player2'][$round-1];
        $card2 = $this->player2->getCardById($card2Id);
        
        $skillCard1 = $card1->get($this->current_skill_playing);
        $skillCard2 = $card2->get($this->current_skill_playing);
        
        if($this->current_skill_playing!='acceleration'){
            if($skillCard1>$skillCard2){
                $this->moves['won'][$round-1] = 'player1';
                $this->next_move_first_player = 'player1';
                $this->player1->addPointToAdd();
            }
            elseif($skillCard1<$skillCard2){
                $this->moves['won'][$round-1] = 'player2';
                $this->next_move_first_player = 'player2';
                $this->player2->addPointToAdd();
            }
        }
        else{
            if($skillCard1<$skillCard2){
                $this->moves['won'][$round-1] = 'player1';
                $this->next_move_first_player = 'player1';
                $this->player1->addPointToAdd();
            }
            elseif($skillCard1>$skillCard2){
                $this->moves['won'][$round-1] = 'player2';
                $this->next_move_first_player = 'player2';
                $this->player2->addPointToAdd();
            }
        }
        
        unset($this->current_skill_playing);
        $this->round++;
    }
    
    public function swipeCardsToWonPlayer(){
        $round = $this->round;
        if(!isset($this->current_skill_playing)&&isset($this->moves['player1'][$round-2])&&isset($this->moves['player2'][$round-2])){
            
            $player1CardId = $this->moves['player1'][$round-2];
            $player2CardId = $this->moves['player2'][$round-2];

            $this->player1->setCardDone($player1CardId);
            $this->player2->setCardDone($player2CardId);
            
            
            return $this->moves['won'][$round-2];
        }
        return false;
    }
    
    public function refreshPoints(){
        $this->player1->refreshPoints();
        $this->player2->refreshPoints();
    }
    
    public function isFinished(){
        if(isset($this->player1)&&isset($this->player2)){
            if($this->player1->hasWon())
                return 'player1';
            
            if($this->player2->hasWon())
                return 'player2';
        }
        
        return false;
    }
}
