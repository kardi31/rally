<?php
session_start();
 ini_set('display_errors', 0);
DEFINE('BASE_PATH',realpath(__DIR__)."/../");
include(BASE_PATH.'/library/TK/Router.php'); 
require_once '../library/Doctrine/Core.php';
spl_autoload_register(array('Doctrine_Core', 'autoload'));
if($_SERVER['SERVER_NAME']=="ral.localhost"){
    $conn = Doctrine_Manager::connection('mysql://root:@localhost/ral', 'connection');
}
elseif($_SERVER['SERVER_NAME']=="switkrzeszowice.hekko24.pl"){
    $conn = Doctrine_Manager::connection('mysql://switkrze_rally:r@lly123@localhost/switkrze_rally', 'connection');
}
$conn->setCharset('utf8');
if(is_dir("/var/www/ral/modules/'.$module.'/models/")){
    Doctrine_Core::setModelsDirectory("/var/www/ral/modules/'.$module.'/models/");
    Doctrine_Core::loadModels('/var/www/ral/modules/'.$module.'/models/');
}
//Doctrine_Manager::getInstance()->getCurrentConnection()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
require(BASE_PATH.'/library/TK/init.php');

new init();
error_reporting(E_ALL);
 
 if(is_dir(BASE_PATH.'/modules/'.$module.'/controllers'))
     require(BASE_PATH.'/modules/'.$module.'/controllers/IndexController.php');
 $contName = ucfirst($module)."_Index";
 $controller = new $contName();
 $controller->render($action);
 
 
?>  
