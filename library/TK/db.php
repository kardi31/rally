<?php

if($_SERVER['SERVER_NAME']=="ral.localhost"){
    $conn = Doctrine_Manager::connection('mysql://kardi3_rally:e0dt9XT3@mysql9.hekko.net.pl/kardi3_rally', 'connection');
//        $this->pdo = new PDO('mysql:host=mysql9.hekko.net.pl;dbname=kardi3_rally;charset=utf8', 'kardi3_rally', 'e0dt9XT3');
//    $conn = Doctrine_Manager::connection('mysql://root:@localhost/ral', 'connection');
}
elseif($_SERVER['SERVER_NAME']=="switkrzeszowice.hekko24.pl"){
    $conn = Doctrine_Manager::connection('mysql://switkrze_rally:r@lly123@localhost/switkrze_rally', 'connection');
}
$conn->setCharset('utf8');