<?php
session_start();
require_once('card/Player.php');
require_once('card/PlayerCollection.php');
require_once('card/TableCollection.php');
require_once('card/Table.php');
$host = 'ral.localhost'; //host
$port = '9000'; //port
$null = NULL; //null var

//Create TCP/IP sream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
//var_dump($_GET);
//bind socket to specified host
socket_bind($socket, 0, $port);

//listen to port
socket_listen($socket);

//create & add listning socket to the list
$clients = array($socket);
$GLOBALS['userSockets'] = array();
$players = new PlayerCollection();
$tables = new TableCollection();

//start endless loop, so that our script doesn't stop
while (true) {
	//manage multipal connections
	$changed = $clients;
	//returns the socket resources in $changed array
	socket_select($changed, $null, $null, 0, 10);
	
	//check for new socket
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket); //accpet new socket
		$clients[] = $socket_new; //add socket to client array
		
		$header = socket_read($socket_new, 1024); //read data sent by the socket
		perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
		
		socket_getpeername($socket_new, $ip); //get ip address of connected socket
//		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
//		send_message($response); //notify all users about new connection
		
                
		$found_socket = array_search($socket, $changed);
                
                
                // no need to refresh because refreshed when type joined is called
                // get list of all players
//                refreshPlayerList($players);
                
                // get list of all tables
                refreshTableList($tables);
                $availableTables = $tables->getAllTables();
                
		//make room for new socket
		unset($changed[$found_socket]);
	}
	//loop through all connected sockets
	foreach ($changed as $changed_socket) {
		//check for any incomming data
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode 
                        if(is_object($tst_msg)){
                            if($tst_msg->type!='updateTimer'){
                            echo " type ---------- ".$tst_msg->type."\r\n";
                            }
                            // joined the game
                            if($tst_msg->type=="joined"){
                                
                                if(!$player = $players->getPlayer($tst_msg->userid)){
                                    $player = $players->addPlayer($tst_msg->userid,$tst_msg->username);
                                    if(!$player){
                                        $notEnoughParams = array('type'=>'notEnoughCards');
                                        echo "zlein";
                                        // show player the table
                                        $response_text = mask(json_encode($notEnoughParams));
                                        send_to_me($player,$response_text); //send data
                                        
                                        $stopScript = true;
                                    }
                                }
                                
                                if(!isset($stopScript)){
                                        $found_socket = array_search($changed_socket, $clients);
                                        $GLOBALS['userSockets'][$tst_msg->userid] = $clients[$found_socket];
                                        if($tableId = $player->getTable()){
                                             $getLeftTimerParameters = array('type' => 'getLeftTimer');
                                             
                                            $table = $tables->getTable($tableId);
                                            $secondPlayer = $table->getOtherPlayer($player);
                                            if($secondPlayer){
                                                $getLeftTimerParameters['user_id'] = $secondPlayer->getId();

                                                $timer_response_text = mask(json_encode($getLeftTimerParameters));
                                                send_to_me($secondPlayer,$timer_response_text);
                                            }
                                        }
                                    refreshPlayerList($players);
                                }
                            }
                            elseif($tst_msg->type=="setLeftTimer"){
                                
                                $player = $players->getPlayer($tst_msg->userid);
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket&&$player->getTable()){
                                    $tableId = $player->getTable();
                                    $table = $tables->getTable($tableId);
//                                    $player
                                    $table->setPlayerBackOnTable($tst_msg->timer);
                                    
                                    $passedParameters = array();
                                    
//                                    $table->setPlayerBackOnTable($player);
                                    $passedParameters['tableid'] = $tableId;
                                    $passedParameters['clearInterval'] = true;
                                    
                                    $userOnTableIds = $table->getPlayerIds();
                                    
                                    $passedParameters = array_merge($passedParameters,$userOnTableIds);
                                    $passedParameters['showTable'] = $table->showTable($userOnTableIds['user_id']);
                                    $passedParameters['showTable2'] = $table->showTable($userOnTableIds['user_id2']);
                                    $passedParameters['type'] = 'showTablePlayer';
                                    
                                    $response_text = mask(json_encode($passedParameters));
                                    send_to_me($player,$response_text);
                                    send_to_other_player_on_table($table,$player,$response_text);
                                    
                                    if($table->isStarted()&&!$table->isTableBeenLeft()&&$onMove = $table->whoseNextMove()){
                                        $moveParameters = array('type' => 'toggleTimer');

                                        $moveParameters = array_merge($moveParameters,$userOnTableIds);

                                        $moveParameters['on_move'] = $onMove;
                                        $response_text = mask(json_encode($moveParameters));
                                        send_to_me($player,$response_text);
                                        send_to_other_player_on_table($table,$player,$response_text);
                                    }
                                }
                            }
                            elseif($tst_msg->type=="setRunAwayTimer"){
                                
                                $player = $players->getPlayer($tst_msg->userid);
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket){
                                    if(!$tableId = $player->getTable()){
                                        $table = $tables->getTable($tableId);
                                    }

                                    $player1 = $table->getPlayer(1);
                                    $player2 = $table->getPlayer(2);

                                    $player1->setTimer($tst_msg->timerPlayer1);
                                    $player2->setTimer($tst_msg->timerPlayer2);

                                    $passedParameters = array();
                                    $table = $tables->getTable($tableId);

                                    $passedParameters['tableid'] = $tableId;

                                    showTableForPlayer($table,$player);
                                }
                            }
                            // New table created
                            elseif($tst_msg->type=="tableJoined"){

                                $player = $players->getPlayer($tst_msg->userid);
                                $passedParameters = array('type'=>'joinedTable');
                                if(!$player->getTable()){
                                    $tableId = $tables->addTable($player);

                                    $table = $tables->getTable($tableId);

                                    $passedParameters['tableid'] = $tableId;
                                    $passedParameters['user_id'] = $tst_msg->userid;

                                    $passedParameters['showTable'] = $table->showTable($tst_msg->userid);

                                }

                                // show player the table
                                $response_text = mask(json_encode($passedParameters));
                                send_to_me($player,$response_text); //send data
                                
                                refreshPlayerList($players);
                                
                                refreshTableList($tables);
                            }
                            // Existing table joined
                            elseif($tst_msg->type=="existTableJoined"){
                                $player = $players->getPlayer($tst_msg->userid);
                                
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket){
                                    $table = $tables->getTable($tst_msg->tableid);


                                    if(!$player->getTable()){
                                        $tables->addPlayerToTable($player,$table);
                                    }

                                    $response_text = mask(json_encode($passedParameters));
                                    
                                    showTableForPlayer($table, $player);
                                    $otherPlayer = $table->getOtherPlayer($player);
                                    showTableForPlayer($table, $otherPlayer);
                                    
                                    refreshPlayerList($players);
                                    refreshTableList($tables);
                                }
                                
                            }
                            
                            // Second player quit the table and his player left timer expired
                            elseif($tst_msg->type=="timeExpired"){
                                $player = $players->getPlayer($tst_msg->userid);
                                
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket){
                                    $table = $tables->getTable($player->getTable());
                                    $table->setPlayerWon($player);
                                    
                                    $secondPlayer = $table->getOtherPlayer($player);
                                    var_dump($table->isFinished());
                                    $table->kickPlayerNoReset($secondPlayer);
                                    $players->removePlayer($secondPlayer->getId(),$tables);
                                    
                                    $table->resetPartiallyTable();
                                    $move2Parameters = array('type' => 'stopAllClocks');
                                    $response_text = mask(json_encode($move2Parameters));
                                    send_to_me($player,$response_text);
                                    showTableForPlayer($table, $player);
                                    
                                    refreshPlayerList($players);
                                    refreshTableList($tables);
                                    
                                }
                            }
                            elseif($tst_msg->type=="timeExpiredFull"){
                                $player = $players->getPlayer($tst_msg->userid);
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket){
                                    $table = $tables->getTable($player->getTable());

    //                                $table->setPlayerWon('player'.$tst_msg->expired);
                                    $secondPlayer = $table->getOtherPlayerByName('player'.$tst_msg->expired);
                                    $player->setPoints(0);
                                    $secondPlayer->setPoints(3);
                                    $table->isFinished();
                                    $passedParameters = array('type'=>'getTableForPlayer');

                                    $response_text = mask(json_encode($passedParameters));
                                    send_to_me($player,$response_text);
                                }
                            }
                            elseif($tst_msg->type=="startTable"){
                                $player = $players->getPlayer($tst_msg->userid);
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket){
                                    $table = $tables->getTable($player->getTable());
                                    
                                    if($table->hasBothPlayers()){
                                        
                                        $table->startTableByPlayer($player);

                                        if($table->isOnePlayerStarted()){
                                            $passedParameters = array('type'=>'showStartTimer');
                                            $userOnTableIds = $table->getPlayerIds();

                                            $passedParameters = array_merge($passedParameters,$userOnTableIds);
                                            $passedParameters['started_user'] = $player->getId(); 

                                            $response_text = mask(json_encode($passedParameters));

                                            send_to_other_player_on_table($table,$player,$response_text);

                                            // show timer but not let player click it
                                            $passedParameters2 = array('type' => 'showStartTimerNotForClick');
                                            $response_text2 = mask(json_encode($passedParameters2));
                                            send_to_me($player,$response_text2);
                                        }
                                        elseif($table->isStarted()){
                                            showTableForPlayer($table, $player,true,true);
                                            $otherPlayer = $table->getOtherPlayer($player);
                                            showTableForPlayer($table, $otherPlayer,true);

                                        }
                                    }
                                }
                            }
                            // Get table just for this player
                            elseif($tst_msg->type=="getPlayerTable"){
                                $player = $players->getPlayer($tst_msg->userid);
                                if($player&&isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket&&$tableid = $player->getTable()){

                                    $passedParameters = array('type'=>'showTablePlayer');

                                    $userOnTableIds = $table->getPlayerIds();

                                    $table = $tables->getTable($tableid);
                                    
                                    $passedParameters = array_merge($passedParameters,$userOnTableIds);
                                    $refreshPoints = false;
                                    if(isset($tst_msg->refreshPoints)){
                                        $refreshPoints = $tst_msg->refreshPoints;
                                    }
                                    $passedParameters['tableid'] = $tableid;
                                    if($table->isFinished()){
                                        $passedParameters['type'] = 'playerWon';
                                        $table->refreshPlayerCards();
                                        $table->refreshPlayerRanks();
                                    }

                                    if(isset($userOnTableIds['user_id'])){
                                        $passedParameters['showTable'] = $table->showTable($userOnTableIds['user_id'],$refreshPoints);
                                    }
                                    if(isset($userOnTableIds['user_id2'])){
                                        $passedParameters['showTable2'] = $table->showTable($userOnTableIds['user_id2'],$refreshPoints);
                                    }
                                    
                                    
                                    if($table->isTableBeenLeft()){
                                        $passedParameters['leftTimer'] = true;
                                    }
                                    
                                    
                                    if(!$table->isStarted()){
                                        $passedParameters['started_user'] = $table->whoStarted(); 
                                    }
                                    
                                    $passedParameters['message'] = $availableTables;
                                    $response_text = mask(json_encode($passedParameters));
                                    send_to_me($player,$response_text); //send data
                                    send_to_other_player_on_table($table, $player, $response_text);
                                    
                                    //&&!$table->isFinished()
                                    
                                    if($table->isStarted()&&!$table->isTableBeenLeft()&&$onMove = $table->whoseNextMove()){
                                        $moveParameters = array('type' => 'toggleTimer');
                                                                                
                                        $moveParameters = array_merge($moveParameters,$userOnTableIds);
                                        
                                        $moveParameters['on_move'] = $onMove;
                                        $response_text = mask(json_encode($moveParameters));
                                        send_to_me($player,$response_text);
                                        
                                        
                                        
                                    }
                                    if($table->isFinished()){
                                        $move2Parameters = array('type' => 'stopAllClocks');
                                        $response_text = mask(json_encode($move2Parameters));
                                        send_to_me($player,$response_text);
                                    }

                                }
                            }
                            elseif($tst_msg->type=="playerNotStartedTable"){
                                $player = $players->getPlayer($tst_msg->userid);
                                if($tableid = $player->getTable()){
                                
                                    $table = $tables->getTable($tableid);
                                    
                                    $otherPlayer = $table->getOtherPlayer($player);
                                    $table->kickPlayer($player);
                                    
                                    

                                    $passedParameters = array('type'=>'showTablePlayer');
                                    
                                    $userOnTableIds = $table->getPlayerIds();
                                    
                                    $passedParameters['tableid'] = $tableid;
                                    if(isset($userOnTableIds['user_id'])){
                                        $passedParameters['showTable'] = $table->showTable($userOnTableIds['user_id']);
                                    }
                                    if(isset($userOnTableIds['user_id2'])){
                                        $passedParameters['showTable2'] = $table->showTable($userOnTableIds['user_id2']);
                                    }

                                    
                                    $passedParameters = array_merge($passedParameters,$userOnTableIds);
                                    $response_text = mask(json_encode($passedParameters));
                                    
                                    // send to other player but no 2nd player object on table anymore
                                    // since we kicked one guy
                                    send_to_me($otherPlayer,$response_text);

                                    
                                    // show timer but not let player click it
                                    $passedParameters2 = array('type' => 'kickMeFromTable');
                                    $response_text2 = mask(json_encode($passedParameters2));
                                    send_to_me($player,$response_text2);
                                    

                                }
                            }
                            // Card clicked
                            elseif($tst_msg->type=="cardClicked"){
                                $player = $players->getPlayer($tst_msg->userid);
                                
                                if(isset($GLOBALS['userSockets'][$player->getId()])&&$GLOBALS['userSockets'][$player->getId()]==$changed_socket){
                                 
                                    $tableId = $player->getTable();
                                    $table = $tables->getTable($tableId);

                                    $moveAccepted = $table->makeAMove($player,$tst_msg->cardNo,$tst_msg->skillNo);
                                    if($moveAccepted){
                                        
                                        // stop the clock when animation is taken place
                                        $move2Parameters = array('type' => 'stopAllClocks');
                                        $response_text_move = mask(json_encode($move2Parameters));
                                        send_to_me($player,$response_text_move);
                                        send_to_other_player_on_table($table,$player,$response_text_move);
                                        
                                        send_to_me($player,$response_text);
                                        send_to_other_player_on_table($table,$player,$response_text);
                                        
                                        
                                        //
                                        $player->setTimer($tst_msg->timer);
                                        $passedParameters = array('type'=>'showTablePlayer');
                                        $userOnTableIds = $table->getPlayerIds();
                                        $passedParameters = array_merge($passedParameters,$userOnTableIds);


                                        $passedParameters['showTable'] = $table->showTable($userOnTableIds['user_id']);
                                        $passedParameters['showTable2'] = $table->showTable($userOnTableIds['user_id2']);

                                        $passedParameters['tableid'] = $tableId;


                                        if($wonPlayer = $table->swipeCardsToWonPlayer()){
                                            $passedParameters['wonPlayer'] = $wonPlayer;
                                        }

                                        $response_text = mask(json_encode($passedParameters));
                                        send_to_me($player,$response_text);
                                        send_to_other_player_on_table($table,$player,$response_text);

                                        if($table->isStarted()&&!$table->isFinished()&&!$table->isTableBeenLeft()&&$onMove = $table->whoseNextMove()){

                                            $isFirstMoveInRound = $table->isFirstMoveInRound();
                                            $moveParameters = array('type' => 'toggleTimer');
                                            $moveParameters = array_merge($moveParameters,$userOnTableIds);
                                            if(isset($passedParameters['wonPlayer'])){
                                                $moveParameters['no_move_clock'] = true;
                                            }
                                            $moveParameters['on_move'] = $onMove;
                                            $response_text3 = mask(json_encode($moveParameters));
                                            send_to_me($player,$response_text3);
                                            send_to_other_player_on_table($table,$player,$response_text3);
                                        }

                                    }
                                }
                            }
                            // Close table
                            elseif($tst_msg->type=="closeTable"){
                                
                                $player = $players->getPlayer($tst_msg->userid);
                                $table = $tables->getTable($tst_msg->tableid);
                                if(!($table->isStarted()&&!$table->isFinished())){
                                    $otherPlayer = $table->getOtherPlayer($player);


                                    $table->resetTableForNewGame();
                                    $tables->closeTable($player,$table);

                                    $passedParameters = array('type'=>'joinedTable');

                                    $availableTables = $tables->getAllTables();
                                    $passedParameters['message'] = $availableTables;
                                    refreshTableList($tables,$passedParameters);

                                    refreshPlayerList($players);

                                    // if there's another player on table
                                    if($otherPlayer){
                                        showTableForPlayer($table, $otherPlayer,false,true,true);
                                    }
                                }

                            }
                            elseif($tst_msg->type=="updateTimer"){
                                if($tst_msg->userid&&$GLOBALS['userSockets'][$tst_msg->userid]==$changed_socket){
                                    if($player = $players->getPlayer($tst_msg->userid)){
                                        $player->setTimer($tst_msg->timer);
                                    }
                                }
                            }
                            elseif($tst_msg->type=="userLeft"){
                                
                                $getTimerParameters = array('type' => 'getTimer');
                                $getTimerParameters['user_id'] = $tst_msg->userid;


                                if($table->isStarted()&&!$table->isFinished()&&$onMove = $table->whoseNextMove()){
                                    $getTimerParameters['on_move'] = $onMove;
                                }

                                $timer_response_text = mask(json_encode($getTimerParameters));
                                send_message($timer_response_text);
                                
                                $response_text = mask(json_encode(array('type'=>'userLeft', 'message'=>'userLeft')));
                                send_message($response_text); //send data
                            }
                        }
                        break 2; //exist this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
                        
                        $userId = array_search($changed_socket,$GLOBALS['userSockets']);
//                        $tableId = $players->removePlayer($userId,$tables);
                        if($player = $players->getPlayer($userId)){
                            if($tableId = $player->getTable()){
                                
                                $table = $tables->getTable($tableId);
                                
                                if($table->isStarted()){

                                    $table->setPlayerLeftTable($player);

                                    $infoArray = array('type'=>'playerLeft', 'message'=>'userLeft');
                                    $userOnTableIds = $table->getPlayerIds();
                                    $infoArray = array_merge($infoArray,$userOnTableIds);

                                    // send to other player info that rival has left the table

                                    $response_text = mask(json_encode($infoArray));
                                    send_to_other_player_on_table($table,$player,$response_text); 
                                }
                                else{
                                    $otherPlayer = $table->getOtherPlayer($player);
                                    $tables->closeTable($player,$table);
//                                    var_dump($otherPlayer);
                                    showTableForPlayer($table, $otherPlayer,false,true);
                                }
                            }
                        }
                        refreshPlayerList($players);
                        
                        refreshTableList($tables); //send data
                        
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
			
			//notify all users about disconnected connection
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected - player '.$userId)));
			send_message($response);
		}
	}
}
// close the listening socket
socket_close($sock);

function send_message($msg)
{
	global $clients;
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}

function send_to_me($player,$msg)
{        
        if($player){
            if(isset($GLOBALS['userSockets'][$player->getId()])&&$foundSocket = $GLOBALS['userSockets'][$player->getId()]){
                echo "send to me \r\n";
                @socket_write($foundSocket,$msg,strlen($msg));
                return true;
            }
        }
        return false;
}

function send_to_other_player_on_table($table,$player,$msg)
{        
        $secondPlayer = $table->getOtherPlayer($player);
        if($secondPlayer){
            if(isset($GLOBALS['userSockets'][$secondPlayer->getId()])&&$foundSocket = $GLOBALS['userSockets'][$secondPlayer->getId()]){
                echo "send to other player \r\n";
                @socket_write($foundSocket,$msg,strlen($msg));
                return true;
            }
        }
        return false;
}


//Unmask incoming framed message
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Encode message for transfer to client.
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	
	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
		$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
		$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}

	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//hand shaking header
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}

function refreshPlayerList($players){
    $message = $players->getJoinedPlayers();
    $response_text = mask(json_encode(array('type'=>'refreshPlayers', 'message'=>$message)));
    send_message($response_text); 
}



function refreshTableList($tables,$parameters = false){
    if(!$parameters){
        $availableTables = $tables->getAllTables();
        $parameters = array('type'=>'refreshTables', 'message'=>$availableTables);
    }
    $response_text = mask(json_encode($parameters));
    send_message($response_text);
}


function showTableForPlayer($table,$player,$toggleTimer = false,$removeStartTimer = false){
    $passedParameters = array('type'=>'showTablePlayer');

    $userOnTableIds = $table->getPlayerIds();
    $passedParameters = array_merge($passedParameters,$userOnTableIds);
                                    
//    $refreshPoints = false;
//    if(isset($tst_msg->refreshPoints)){
//        $refreshPoints = $tst_msg->refreshPoints;
//
//    }

    
    if($removeStartTimer){
        $passedParameters['remove_start_timer'] = true; 
    }
    
    if(isset($userOnTableIds['user_id'])){
        $passedParameters['showTable'] = $table->showTable($userOnTableIds['user_id']);
    }
    if(isset($userOnTableIds['user_id2'])){
        $passedParameters['showTable2'] = $table->showTable($userOnTableIds['user_id2']);
    }
    $passedParameters['tableid'] = $table->getId();

    if($table->isFinished()){
        $passedParameters['type'] = 'playerWon';
    }
                    
    if($table->isTableBeenLeft()){
        $passedParameters['leftTimer'] = true;
    }
                                    
//                                  if(!$table->isStarted()){
//                                        $passedParameters['started_user'] = $table->whoStarted(); 
//                                    }
                                    
    $response_text = mask(json_encode($passedParameters));
    send_to_me($player,$response_text); //send data
    //&&!$table->isFinished()

    if($toggleTimer){
        if($table->isStarted()&&!$table->isTableBeenLeft()&&$onMove = $table->whoseNextMove()){
            $moveParameters = array('type' => 'toggleTimer');

            $moveParameters = array_merge($moveParameters,$userOnTableIds);

            $moveParameters['on_move'] = $onMove;
            $response_text = mask(json_encode($moveParameters));
            send_to_me($player,$response_text);
        }
        if($table->isFinished()){
            $move2Parameters = array('type' => 'stopAllClocks');
            $response_text = mask(json_encode($move2Parameters));
            send_to_me($player,$response_text);
        }
    }
}