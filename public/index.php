<?php
session_start();

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];



ini_set('display_errors', 1);
DEFINE('BASE_PATH',realpath(__DIR__)."/../");
include(BASE_PATH.'/library/TK/Router.php'); 
require_once '../library/Doctrine/Core.php';
spl_autoload_register(array('Doctrine_Core', 'autoload'));
require_once '../library/TK/db.php';

if(is_dir("/var/www/ral/modules/'.$module.'/models/")){
    Doctrine_Core::setModelsDirectory("/var/www/ral/modules/'.$module.'/models/");
    Doctrine_Core::loadModels('/var/www/ral/modules/'.$module.'/models/');
}
require(BASE_PATH.'/library/TK/init.php');

new init();
 
ini_set("log_errors", 1);
ini_set("error_log", BASE_PATH."/logs/php-error.log");

 if(is_dir(BASE_PATH.'/modules/'.$module.'/controllers'))
     require(BASE_PATH.'/modules/'.$module.'/controllers/IndexController.php');
 $contName = ucfirst($module)."_Index";
 $controller = new $contName();
 $controller->render($action);
 
 
?>  
