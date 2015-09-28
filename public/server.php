<?php
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
$userSockets = array();
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
		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
		send_message($response); //notify all users about new connection
		
		//make room for new socket
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
                
                // request username and id
                $response_text = mask(json_encode(array('type'=>'requestData')));
                send_message($response_text); //send data
                
                // get list of all players
                $message = $players->getJoinedPlayers();
                $response_text = mask(json_encode(array('type'=>'joined', 'message'=>$message)));
                send_message($response_text);
                
                // get list of all tables
                $availableTables = $tables->getAllTables();
                $response_text = mask(json_encode(array('type'=>'joinedTable', 'message'=>$availableTables)));
                send_message($response_text);
	}
	
	//loop through all connected sockets
	foreach ($changed as $changed_socket) {
		//check for any incomming data
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode 
                        
                        if(is_object($tst_msg)&&$tst_msg->type=="joined"){
                            
                            if(!$players->getPlayer($tst_msg->userid)){
                                $player = $players->addPlayer($tst_msg->userid,$tst_msg->username,json_decode($tst_msg->cards,true));
                                
                                $found_socket = array_search($changed_socket, $clients);
                                $userSockets[$tst_msg->userid] = $clients[$found_socket];
                            }
                            
                            refreshPlayerList($players);
                            
                        }
                        elseif(is_object($tst_msg)&&$tst_msg->type=='refreshed'){
                            
                            if($player = $players->getPlayer($tst_msg->userid)){
                                if($player->getTable()){
                                    $tableId = $player->getTable();
                                    $table = $tables->getTable($tableId);

                                    $passedParameters = array('type'=>'joinedTable');
                                    $passedParameters['tableid'] = $tableId;
                                    $passedParameters['showTable'] = $table->showTable();
                                    $userOnTableIds = $table->getPlayerIds();
                                    $passedParameters = array_merge($passedParameters,$userOnTableIds);

                                    $response_text = mask(json_encode($passedParameters));
                                    send_message($response_text); //send data

                                }
                            }
                        }
                        // New table created
                        elseif(is_object($tst_msg)&&$tst_msg->type=="tableJoined"){
                            
                            $player = $players->getPlayer($tst_msg->userid);
                            $passedParameters = array('type'=>'joinedTable');
                                
                            if(!$player->getTable()){
                                $tableId = $tables->addTable($player);
                                
                                $table = $tables->getTable($tableId);
                                
                                $passedParameters['tableid'] = $tableId;
                                $passedParameters['user_id'] = $tst_msg->userid;
                                
                                $passedParameters['showTable'] = $table->showTable();
                                
                            }
                            $availableTables = $tables->getAllTables();
                            $passedParameters['message'] = $availableTables;
                            
                            $response_text = mask(json_encode($passedParameters));
                            send_message($response_text); //send data
                        }
                        // Existing table joined
                        elseif(is_object($tst_msg)&&$tst_msg->type=="existTableJoined"){
                            $player = $players->getPlayer($tst_msg->userid);
                            $table = $tables->getTable($tst_msg->tableid);
                            
                            $passedParameters = array('type'=>'joinedTable');
                            
                            if(!$player->getTable()){
                                $tables->addPlayerToTable($player,$table);
                                $passedParameters['tableid'] = $tableId;
                                $passedParameters['showTable'] = $table->showTable();
                                $userOnTableIds = $table->getPlayerIds();
                                $passedParameters = array_merge($passedParameters,$userOnTableIds);
                                
                            }
                            $availableTables = $tables->getAllTables();
                            
                            $passedParameters['message'] = $availableTables;
                            
                            $response_text = mask(json_encode($passedParameters));
                            send_message($response_text); //send data
                        }
                        
                        // Close table
                        elseif(is_object($tst_msg)&&$tst_msg->type=="closeTable"){
                            
                            $player = $players->getPlayer($tst_msg->userid);
                            $table = $tables->getTable($tst_msg->tableid);
                            
                            $tables->closeTable($player,$table);
                            
                            $passedParameters = array('type'=>'joinedTable');
                            
                            $availableTables = $tables->getAllTables();
                            $passedParameters['message'] = $availableTables;
                            refreshTableList($tables,$passedParameters);
                            
                            // if table still exists
                            // which means has existing user on table
                            if($tables->getTable($tst_msg->tableid)){
                                $newParameters = array('type'=>'joinedTable');
                                $newParameters['tableid'] = $tableId;
                                $newParameters['showTable'] = $table->showTable();
                                $remainingPlayerId = $table->getPlayerIds();
                                $newParameters = array_merge($newParameters,$remainingPlayerId);
                                $response_text = mask(json_encode($newParameters));
                                send_message($response_text);
                            }
                            
                        }
                        elseif(is_object($tst_msg)&&$tst_msg->type=="userLeft"){
                            
//                            $player = $players->getPlayer($tst_msg->userid);
//                            $tables->addTable($player);
//                            
//                            $availableTables = $tables->getAllTables();
                            $response_text = mask(json_encode(array('type'=>'userLeft', 'message'=>'userLeft')));
                            send_message($response_text); //send data
                        }
                        elseif(is_object($tst_msg)){
                            $user_name = $tst_msg->name; //sender name
                            $user_message = $received_text;
                            $user_color = $tst_msg->color; //color

                            //prepare data to be sent to client
                            $response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color)));
                            send_message($response_text); //send data
			
                        }
                        break 2; //exist this loop
                        
//			$user_name = $tst_msg->name; //sender name
//			$user_message = $tst_msg->message; //message text
//			$user_color = $tst_msg->color; //color
//			
//			//prepare data to be sent to client
//			$response_text = mask(json_encode(array('type'=>'usermsg', 'name'=>$user_name, 'message'=>$user_message, 'color'=>$user_color)));
//			send_message($response_text); //send data
//			break 2; //exist this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
                        
                        $userId = array_search($changed_socket,$userSockets);
                        
                        $players->removePlayer($userId,$tables);
                        
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

    $response_text = mask(json_encode(array('type'=>'joined', 'message'=>$message)));
    send_message($response_text); 
}

function refreshTableList($tables,$parameters = false){
    if(!$parameters){
        $availableTables = $tables->getAllTables();
        $parameters = array('type'=>'joinedTable', 'message'=>$availableTables);
    }
    $response_text = mask(json_encode($parameters));
    send_message($response_text);
}
