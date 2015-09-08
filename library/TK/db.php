<?php

if($_SERVER['SERVER_NAME']=="ral.localhost"){
    $conn = Doctrine_Manager::connection('mysql://switkrze_rally:r@lly123@s41.hekko.net.pl/switkrze_rally', 'connection');
//    $conn = Doctrine_Manager::connection('mysql://root:@localhost/ral', 'connection');
}
elseif($_SERVER['SERVER_NAME']=="switkrzeszowice.hekko24.pl"){
    $conn = Doctrine_Manager::connection('mysql://switkrze_rally:r@lly123@localhost/switkrze_rally', 'connection');
}
$conn->setCharset('utf8');