<?php
require_once(__DIR__."/PlayerCollection.php");

class Table {
    
    protected $player1;
    protected $player2;
    protected $id;
    protected $moves = array(
        'player1' => array(),
        'player2' => array(),
        'won' => array(),
        'wantStart' => array()
    );
    protected $current_skill_playing;
    protected $skillOrder = array('acceleration','max_speed','capacity','horsepower');
    protected $round = 1;
    protected $is_started = false;
    
    protected $started_moves = array();
    protected $finished_moves = array();

    protected $is_finished = false;
    protected $card_won = false;
    

    protected $players_left_table = array();
    protected $invited_users = array();
    
    protected $admin_id;
    
    
    public function resetPartiallyTable(){
        $this->players_left_table = array();
        $this->started_moves = array();
        $this->finished_moves = array();
        $this->moves = array(
            'player1' => array(),
            'player2' => array(),
            'won' => array(),
            'wantStart' => array()
        );
        $this->round = 1;
        $this->invited_users = array();
        unset($this->current_skill_playing);
    }
    
    protected function resetPartiallyTable2(){
        $this->card_won = false;
        $this->is_finished = false;
        $this->player1->resetPlayerInfo();
        $this->player2->resetPlayerInfo();
    }
    
    protected function resetTableNoPlayers(){
        $this->players_left_table = array();
        $this->started_moves = array();
        $this->finished_moves = array();
        $this->moves = array(
            'player1' => array(),
            'player2' => array(),
            'won' => array(),
            'wantStart' => array()
        );
        $this->round = 1;
        unset($this->current_skill_playing);
        
        $this->card_won = false;
        $this->is_finished = false;
    }
    public function __construct($player,$id){
        $this->player1 = $player;
        $this->id = $id;
        $this->admin_id = $player->getId();
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
    
    
    public function getPlayer($position=1){
        return $this->{'player'.$position};
    }
    
    public function addPlayer($player){
        if(isset($this->player1)){
            $this->player2 = $player;
        }
        else{
            $this->player1 = $player;
        }
//        $this->setStartingPlayer();
    }
    
    public function setStartingPlayer(){
//        $playerNo = rand(1,2);
       
        $playerNo = 1;
        $this->started_moves[0][] = 'player'.$playerNo;
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
        var_dump('remove-player');
        if($this->player1==$player){
        var_dump('remove-player1');
            unset($this->player1);
        }
        elseif($this->player2==$player){
        var_dump('remove-player2');
            unset($this->player2);
        }
    }
    
    
    public function getSkill($skillId){
        return $this->skillOrder[$skillId-1];
    }
    
    public function makeAMove($player,$cardOrderNo,$skillId){
        
        // check first move
        
        $round = $this->round;
        
        // 1 ruch w danej kolejce
        if(count($this->started_moves[$round-1])==1&&!isset($this->finished_moves[$round-1]))
        {
            $playerWhoMove = $this->{$this->started_moves[$round-1][0]};
            $playerWhoMovePlayerName = $this->started_moves[$round-1][0];
            if($player==$playerWhoMove){
                $card = $player->getCard($cardOrderNo);
                $this->moves[$playerWhoMovePlayerName][] = $card->getId();
                $this->finished_moves[$round-1][] = $playerWhoMovePlayerName;
                if($playerWhoMovePlayerName=='player1'){
                    $this->started_moves[$round-1][] = 'player2';
                }
                elseif($playerWhoMovePlayerName=='player2'){
                    $this->started_moves[$round-1][] = 'player1';
                }
                $this->setCurrentSkillPlaying($skillId);
                return true;
            }
        }
        // ruch drugiego gracza w danej kolejce
        elseif(count($this->started_moves[$round-1])==2&&isset($this->finished_moves[$round-1])&&count($this->finished_moves[$round-1])==1)
        {
            $playerWhoMove = $this->{$this->started_moves[$round-1][1]};
            $playerWhoMovePlayerName = $this->started_moves[$round-1][1];
            if($player==$playerWhoMove){
                $requestedSkill = $this->getSkill($skillId);
                if(isset($this->current_skill_playing)&&$this->current_skill_playing==$requestedSkill){
                    $previousPlayer = $this->{$this->started_moves[$round-1][0]};
                    $previousPlayerPlayerName = $this->started_moves[$round-1][0];
                    
                    // when both players chosen their cards
                    // open both cards at once
                    $previousPlayer->openCard($this->moves[$previousPlayerPlayerName][$round-1],$skillId);
                    
                    $card = $player->getCard($cardOrderNo);
                    $player->openCard($card->getId(),$skillId);

                    $this->finished_moves[$round-1][] = $playerWhoMovePlayerName;
                    $this->moves[$playerWhoMovePlayerName][] = $card->getId();
                    $this->checkCardWon($this->round);
                    return true;
                }
            }
        }
        // runda zakonczona
//        elseif(count($this->started_moves[$round-1])==count($this->finished_moves[$round-1])){
//            
//        }
        
        return false;
        
        
    }
    
    public function showTable($user_id = null,$refreshPoints = false){
        $this->whoseNextMove();
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
        if(!$this->isFinished()&&$this->isStarted()){
            echo "--------not finished and started \r\n";
            if($playerLeft = $this->isTableBeenLeft()){

                $playerLeftBtn = $dom->createElement('button',$this->{$playerLeft}->getUsername()." has left the table.");
                $leftTimer = $dom->createElement('span',"00:30");
                $playerLeftBtn->setAttribute('class', 'playerLeft');
                $playerLeftBtn->setAttribute('disabled', 'disabled');
                $playerLeftBtn->appendChild($leftTimer);
                $playField->appendChild($playerLeftBtn);
            }
            else{
                if($this->isGameJustStarted()){
                    $roundInfo = $dom->createElement('span',$this->{$this->started_moves[0][0]}->getUsername()." starts the game");

                    $whoStartInfo = $dom->createElement('div','Round '.$this->round);
                    $whoStartInfo->setAttribute('class', 'gameInformation');
                    $whoStartInfo->setAttribute('data-rel', $this->started_moves[0][0]);
                    $whoStartInfo->appendChild($roundInfo);
                    $playField->appendChild($whoStartInfo);
                }
                elseif(!$this->isRoundFinished()&&$this->whoseNextMove()){
                    $whoStartInfo = $dom->createElement('div','Round '.$this->round);
                    $whoStartInfo->setAttribute('class', 'gameInformation');
                    $nextMoveObject = $this->whoseNextMove();
                    $roundInfo = $dom->createElement('span',$this->{$nextMoveObject['who']}->getUsername()." do next move");

                    $whoStartInfo->appendChild($roundInfo);
                    $playField->appendChild($whoStartInfo);
                }
            }
        }
        elseif(!$this->isStarted()){
            echo "--------not started \r\n";
            if(!$notEnough = $this->notEnoughCardsForPlayers()){
                $startGameBtn = $dom->createElement('button','Start the game');
                $startGameBtn->setAttribute('class', 'startGame startGameNow');

                $playField->appendChild($startGameBtn);
            }
            else{
                $notEnoughCardsInfo = $dom->createElement('div',$notEnough->getUsername()." has not enough cards to play");
                $notEnoughCardsInfo->setAttribute('class', 'notEnoughCardsInfo');

                $playField->appendChild($notEnoughCardsInfo);
            }
        }
        else{
            
            echo "--------good \r\n";
            $player1Cards->setAttribute('class','playerCards done');
            $player2Cards->setAttribute('class','playerCards done');
            
            
//            $startGameBtn = $dom->createElement('button','Start the game');
//            $startGameBtn->setAttribute('class', 'startGame');
            $whoWon = $dom->createElement('div');
            $roundInfo = $dom->createElement('span',$this->{$this->isFinished()}->getUsername()." has won the game");
            $whoWon->setAttribute('class', 'gameInformation wonTable');
            
            $whoWon->appendChild($roundInfo);
            
            
            
            
            if($this->card_won){
                $cardWon = $this->card_won;
                $wonCardElement = $this->createPlayerCardTable($cardWon,$dom);
                
                $wonCardWrapper = $dom->createElement('div');
                $wonCardWrapper->setAttribute('class', 'wonCardWrapper');
                
                $wonCardPreWrapper = $dom->createElement('div');
                $wonCardPreWrapper->setAttribute('class', 'wonCardPreWrapper');
                
                
                
                $wonCardWrapper->appendChild($wonCardElement);
                $wonCardPreWrapper->appendChild($wonCardWrapper);
                
                 $this->resetPartiallyTable();
            }
            
            
            $playField->appendChild($whoWon);
            $playField->appendChild($wonCardPreWrapper);
            
            if(!$notEnough = $this->notEnoughCardsForPlayers()){
                $startGameBtn = $dom->createElement('button','Start the game');
                $startGameBtn->setAttribute('class', 'startGame startGameNow');

                $playField->appendChild($startGameBtn);
            }
            else{
                $notEnoughCardsInfo = $dom->createElement('div',$notEnough->getUsername()." has not enough cards to play");
                $notEnoughCardsInfo->setAttribute('class', 'notEnoughCardsInfo');

                $playField->appendChild($notEnoughCardsInfo);
            }
            
            
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
        
        // player list start
        
        $playerList = $dom->createElement('div');
        $playerList->setAttribute('class', 'playerList');
        
        if(isset($this->player1)){
            $player1Cards->setAttribute('data-id', $this->player1->getId());
            $player1 = $dom->createElement('div',$this->player1->getUsername());
            
            $player1Points = $dom->createElement('div',$this->player1->getPoints());
            $player1Points->setAttribute('class', 'playerPoints');
            
            $player1Rank = $dom->createElement('div',$this->player1->getRank());
            $player1Rank->setAttribute('class', 'playerRank');
            
            $player1Timer = $dom->createElement('span',$this->player1->getTimer());
            $player1Timer->setAttribute('class','playerTimer');
            
            $player1->appendChild($player1Rank);
            $player1->appendChild($player1Timer);
            $player1->appendChild($player1Points);
        }
        else{
            $player1 = $dom->createElement('div','&nbsp;');
        }
        $playerList->appendChild($player1);
        
        if(isset($this->player2)){
            $player2Cards->setAttribute('data-id', $this->player2->getId());
            $player2 = $dom->createElement('div',$this->player2->getUsername());
            
            
            $player2Points = $dom->createElement('div',$this->player2->getPoints());
            $player2Points->setAttribute('class', 'playerPoints');
            
            $player2Rank = $dom->createElement('div',$this->player2->getRank());
            $player2Rank->setAttribute('class', 'playerRank');
            
            $player2Timer = $dom->createElement('span',$this->player2->getTimer());
            $player2Timer->setAttribute('class','playerTimer');
            
            $player2->appendChild($player2Rank);
            $player2->appendChild($player2Timer);
            $player2->appendChild($player2Points);
            
        }
        else{
            $player2 = $dom->createElement('div','&nbsp;');
        }
        
        $playerList->appendChild($player2);
            
        $cardTableInfo->appendChild($closeTable);
        if(isset($tableId)){
            $tableIdBox = $dom->createElement('div','Table '.$tableId);
            $tableIdBox->setAttribute('class', 'tableName');
            $cardTableInfo->appendChild($tableIdBox);
        }
        
        $cardTableInfo->appendChild($playerList);
        
        // small boxes to the right
        
        $smallBoxes = $dom->createElement('div');
        $smallBoxes->setAttribute('class', 'smallBoxes');
        
        $smallBoxesMenu = $dom->createElement('ul');
        
        $smallBoxesMenuItem = $dom->createElement('li','User list');
        $smallBoxesMenuItem->setAttribute('data-rel','userList');
        if((isset($_COOKIE['smallBoxes'])&&$_COOKIE['smallBoxes']=='userList')||!isset($_COOKIE['smallBoxes'])){
            $smallBoxesMenuItem->setAttribute('class','active');
        }
        
        $smallBoxesMenuItem2 = $dom->createElement('li','On table');
        $smallBoxesMenuItem2->setAttribute('data-rel','onTableList');
        if(isset($_COOKIE['onTableList'])&&$_COOKIE['smallBoxes']=='onTableList'){
            $smallBoxesMenuItem2->setAttribute('class','active');
        }
        
        $smallBoxesMenu->appendChild($smallBoxesMenuItem);
        $smallBoxesMenu->appendChild($smallBoxesMenuItem2);
        $smallBoxes->appendChild($smallBoxesMenu);
        
        // user list
        
        $smallBoxesUserList = $dom->createElement('div');
        $smallBoxesUserList->setAttribute('class', 'userList');
        
        var_dump($_COOKIE);
        if((isset($_COOKIE['smallBoxes'])&&$_COOKIE['smallBoxes']=='userList')||!isset($_COOKIE['smallBoxes'])){
            $smallBoxesUserList->setAttribute('style','display:block');
        }
        
        $joinedPlayers = $dom->importNode(PlayerCollection::getInstance()->getJoinedPlayersTable(true),true);
        $smallBoxesUserList->appendChild($joinedPlayers);
        
        $smallBoxesOnTable = $dom->createElement('div');
        $smallBoxesOnTable->setAttribute('class', 'onTableList');
        
        if(isset($_COOKIE['onTableList'])&&$_COOKIE['smallBoxes']=='onTableList'){
            $smallBoxesOnTable->setAttribute('style','display:block');
        }
        // on table
        
        $onTable = $dom->importNode($this->getPlayersOnTable($user_id),true);
        $smallBoxesOnTable->appendChild($onTable);
        
        $smallBoxes->appendChild($smallBoxesUserList);
        $smallBoxes->appendChild($smallBoxesOnTable);
        
        $cardTableInfo->appendChild($smallBoxes);
        
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
            $playerIds['user_id2'] = $this->player2->getId();
        }
        return $playerIds;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function createPlayerCardTable($card,$dom,$whichPlayer = false){
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
        if(is_int($currentPlayingSkillId)&&$whichPlayer){ // &&$card->isOpened()
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
                $this->started_moves[$round][] = 'player1';
                $this->player1->addPointToAdd();
            }
            elseif($skillCard1<$skillCard2){
                $this->moves['won'][$round-1] = 'player2';
                $this->started_moves[$round][] = 'player2';
                $this->player2->addPointToAdd();
            }
            // skillcard1 = skillcard2
            else{
                $this->moves['won'][$round-1] = 'draw';
                if(isset($this->started_moves[$round-1][0])){
                    $this->started_moves[$round][] = $this->started_moves[$round-1][0];
                }
                $this->player1->addPointToAdd();
                $this->player2->addPointToAdd();
            }
        }
        else{
            if($skillCard1<$skillCard2){
                $this->moves['won'][$round-1] = 'player1';
                $this->started_moves[$round][] = 'player1';
                $this->player1->addPointToAdd();
            }
            elseif($skillCard1>$skillCard2){
                $this->moves['won'][$round-1] = 'player2';
                $this->started_moves[$round][] = 'player2';
                $this->player2->addPointToAdd();
            }
            
            // skillcard1 = skillcard2
            else{
                $this->moves['won'][$round-1] = 'draw';
                if(isset($this->started_moves[$round-1][0])){
                    $this->started_moves[$round][] = $this->started_moves[$round-1][0];
                }
                $this->player1->addPointToAdd();
                $this->player2->addPointToAdd();
            }
        }
    }
    
    
    
    public function swipeCardsToWonPlayer(){
        $round = $this->round;
        if(isset($this->moves['player1'][$round-1])&&isset($this->moves['player2'][$round-1])){
            
            $player1CardId = $this->moves['player1'][$round-1];
            $player2CardId = $this->moves['player2'][$round-1];

            $this->player1->setCardDone($player1CardId);
            $this->player2->setCardDone($player2CardId);
            
            
            unset($this->current_skill_playing);
            $this->round++;
            return $this->moves['won'][$round-1];
        }
        return false;
    }
    
    public function isRoundFinished(){
        $round = $this->round;
        
        if(isset($this->finished_moves[$round-1])&&count($this->finished_moves[$round-1])==2){
            return true;
        }
        return false;
    }
    
    public function refreshPoints(){
        $this->player1->refreshPoints();
        $this->player2->refreshPoints();
    }
    
    public function setPlayerWon($player){
        $player->setPoints(3);
    }
    
    public function isFinished(){
        
        if($this->is_finished)
            return $this->is_finished;
        
        if(isset($this->player1)&&isset($this->player2)){
            
            if($this->player1->hasWon()){
                $wonCard = $this->player2->moveLostCard($this->player1->getId());
                $this->card_won = $wonCard;
                $this->setFinished('player1');
                return 'player1';
            }
//        if($this->player_won){
//            return $this->player_won;
//        }
            if($this->player2->hasWon())
            {
                $wonCard = $this->player1->moveLostCard($this->player2->getId());
                $this->card_won = $wonCard;
                $this->setFinished('player2');
                return 'player2';
            }
        }
        return false;
    }
    
    public function setFinished($playerWon){
        $this->is_finished = $playerWon;
    }
    
    public function isStarted(){
        return $this->is_started;
    }
    
    public function startTableByPlayer($player){
        if($player==$this->player1){
            array_push($this->moves['wantStart'],'player1');
        }
        elseif($player==$this->player2){
            array_push($this->moves['wantStart'],'player2');
        }
        if(isset($this->moves['wantStart'][0])&&isset($this->moves['wantStart'][1])){
            if(in_array('player1',$this->moves['wantStart'])&&in_array('player2',$this->moves['wantStart'])){
                $this->setStartingPlayer();
                $this->resetPartiallyTable2();
                $this->is_started = true;
                unset($this->moves['wantStart'][0]);
                unset($this->moves['wantStart'][1]);
            }
        }
    }
    
    public function whoStarted(){
        if(isset($this->moves['wantStart'][0])){
            return $this->moves['wantStart'][0];
        }
        
        return false;
    }
    
    public function isOnePlayerStarted(){
        if(isset($this->moves['wantStart'][0])&&!isset($this->moves['wantStart'][1])){
            return true;
        }
        
        return false;
    }
    
    public function getOtherPlayer($player){
        if(isset($this->player1)&&$player==$this->player1){
            return $this->player2;
        }
        elseif(isset($this->player2)&&$player==$this->player2){
            return $this->player1;
        }
        
        return false;
    }
    
    public function getOtherPlayerByName($player){
        if($player=='player1'){
            return $this->player2;
        }
        elseif($player=='player2'){
            return $this->player1;
        }
        
        return false;
    }
    
    public function whoseNextMove(){
        
        $round = $this->round;
        // 1 ruch w danej kolejce
        if(isset($this->started_moves[$round-1])&&count($this->started_moves[$round-1])==1&&!isset($this->finished_moves[$round-1]))
        {
            $nextMove = $this->started_moves[$round-1][0];
        }
        // ruch drugiego gracza w danej kolejce
        elseif(isset($this->started_moves[$round-1])&&count($this->started_moves[$round-1])==2&&isset($this->finished_moves[$round-1])&&count($this->finished_moves[$round-1])==1)
        {
            $nextMove = $this->started_moves[$round-1][1];
        }
        // runda zakonczona
//        elseif(count($this->started_moves[$round-1])==count($this->finished_moves[$round-1])){
//            
//        }
        
        if(isset($nextMove)){
            $result = array('who' => $nextMove,'whoShort' => substr($nextMove,6));
            $result['ms'] = $this->{$nextMove}->getTimer(true);
            $result['user_id'] = $this->{$nextMove}->getId();
            return $result;
        }
        
        return false;
    }
    
    public function isFirstMoveInRound(){
         $round = $this->round;
        // 1 ruch w danej kolejce
        if(isset($this->started_moves[$round-1])&&count($this->started_moves[$round-1])==1&&!isset($this->finished_moves[$round-1]))
        {
            return true;
        }
        return false;
    }
    
    public function setPlayerLeftTable($player){
        if($player== $this->player1){
            $this->players_left_table[$player->getId()] = 'player1';
        }
        elseif($player== $this->player2){
            $this->players_left_table[$player->getId()] = 'player2';
        }
    }
    
    public function isTableBeenLeft(){
        if(!empty($this->players_left_table)){
            $key = key($this->players_left_table);
            return $this->players_left_table[$key];
        }
        
        return false;
    }
    
    public function setPlayerBackOnTable($timer){
        $key = key($this->players_left_table);
        if(isset($this->players_left_table[$key])){
            
            if($this->player1->getId()==$key){
                $player = $this->player1;
            }
            elseif($this->player2->getId()==$key){
                $player = $this->player2;
            }
            
            // current timer
            $playerTimer = $player->getTimer(true);
            
            // how much time left on player left timer
            $msLeftOnTimer = $player->getMilisecondsFromTime($timer);
            
            $sec30 = 1000*30;
            
            $msToTakeFromTime = $sec30 - $msLeftOnTimer;
            
            $newPlayerTimer = $playerTimer-$msToTakeFromTime;
            
            $min = floor($newPlayerTimer / (60 * 1000));
            $sec = floor(($newPlayerTimer - ($min * 60 * 1000)) / 1000);  //correct
           
//            var_dump($timer);
            $player->setTimer($min." : ".$sec);
            
            
            unset($this->players_left_table[$player->getId()]);
        }
    }
    
    public function kickPlayer($player){
        if($this->player1==$player){
            unset($this->player1);
            
        }
        elseif($this->player2==$player){
            unset($this->player2);
        }
        $this->resetTableNoPlayers();
        $player->removeTable();
    }
    
    public function kickPlayerNoReset($player){
        if($this->player1==$player){
            unset($this->player1);
            
        }
        elseif($this->player2==$player){
            unset($this->player2);
        }
        $player->removeTable();
    }
    
    public function refreshPlayerCards(){
        if(isset($this->player1)){
            $this->player1->setPlayerCards();
        }

        if(isset($this->player2)){
            $this->player2->setPlayerCards();
        }
        
        return true;
    }
    
    public function refreshPlayerRanks(){
        $player1OldRank = $this->player1->getRank();
        $player2OldRank = $this->player2->getRank();
        $offset = 15;
        
        
        if($this->player1->hasWon()){
            
            $player1NewRank = $player1OldRank + $offset + ceil(($player2OldRank-1000)/$offset);
            $player2NewRank = $player2OldRank - $offset + ceil(($player1OldRank-1000)/$offset);
        }
        else{
            $player1NewRank = $player1OldRank - $offset + ceil(($player2OldRank-1000)/$offset);
            $player2NewRank = $player2OldRank + $offset + ceil(($player1OldRank-1000)/$offset);
        }
        
        $this->player1->updateRank($player1NewRank);
        $this->player2->updateRank($player2NewRank);
    }
    
    public function resetTableForNewGame(){
        $this->players_left_table = array();
        $this->player_won = false;
        $this->started_moves = array();
        $this->finished_moves = array();
        $this->moves = array(
            'player1' => array(),
            'player2' => array(),
            'won' => array(),
            'wantStart' => array()
        );
        $this->round = 1;
        unset($this->current_skill_playing);
        $this->card_won = false;
        $this->is_finished = false;
        $this->is_started = false;
        if(isset($this->player1)){
            $this->player1->resetPlayerInfo();
        }
        if(isset($this->player2)){
            $this->player2->resetPlayerInfo();
        }
    }
    
    public function notEnoughCardsForPlayers(){
        
        if(isset($this->player1)){
            $result1 = $this->player1->hasEnoughCards();
            if(!$result1){
                return $this->player1;
            }
        }
        if(isset($this->player2)){
            $result2 = $this->player2->hasEnoughCards();
            if(!$result2){
                return $this->player2;
            }
        }
        
        return false;
    }
    
    public function getInviteText($player){
        
        $dom = new DOMDocument();
        
        $inviteText = $dom->createElement('span',$player->getUsername()." invites you to table ".$this->id);
        $inviteText->setAttribute('class', 'invitedToTable');
        
        $inviteLink = $dom->createElement('a','Join table');
        $inviteLink->setAttribute('href', 'javascript:void(0)');
        $inviteLink->setAttribute('class', 'joinSingleTable');
        
        
        $closeTable = $dom->createElement('button','X');
        $closeTable->setAttribute('class', 'closeInvitation');
        
        $inviteLink->setAttribute('data-table', $this->id);
        
        $inviteText->appendChild($inviteLink);
        $inviteText->appendChild($closeTable);
        
        $dom->appendChild($inviteText);
        
        return $dom->saveHTML();
    }
    
    public function addInvitedPlayer($username){
        array_push($this->invited_users,$username);
    }
    
    public function isAlreadyInvited($username){
        if(in_array($username,$this->invited_users)){
            return true;
        }
        
        return false;
    }
    
    public function getPlayersOnTable($user_id){
        
        $dom = new DOMDocument();
        
        $playersOnTable = $dom->createElement('ul');
        
        if(isset($this->player1)){
            $li = $dom->createElement('li',$this->player1->getUsername());
            $spanRank = $dom->createElement('span',$this->player1->getRank());
            $spanRank->setAttribute('class', 'rank');
            if($user_id==$this->admin_id&&$this->player1->getId()!=$user_id){
                $a = $dom->createElement('button','X');
                $a->setAttribute('class', 'kickPlayerFromTable');
                $a->setAttribute('data-rel', $this->player1->getId());
            $li->appendChild($a);
            }
            $li->appendChild($spanRank);
            $playersOnTable->appendChild($li);
        }
        
        if(isset($this->player2)){
            $li = $dom->createElement('li',$this->player2->getUsername());
            $spanRank = $dom->createElement('span',$this->player2->getRank());
            $spanRank->setAttribute('class', 'rank');
            
            if($user_id==$this->admin_id&&$this->player2->getId()!=$user_id){
                $a = $dom->createElement('button','X');
                $a->setAttribute('class', 'kickPlayerFromTable');
                $a->setAttribute('data-rel', $this->player2->getId());
            $li->appendChild($a);
            }
            $li->appendChild($spanRank);
            $playersOnTable->appendChild($li);
        }
        
        return $playersOnTable;
    }
    
    public function isPlayerAdmin($player){
        if($player->getId()==$this->admin_id){
            return true;
        }
        return false;
    }
    
}
