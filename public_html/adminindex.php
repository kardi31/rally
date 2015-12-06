<?php

session_start();
DEFINE('BASE_PATH',realpath(__DIR__)."/../");
include(BASE_PATH.'/library/TK/AdminRouter.php');
require_once '../library/Doctrine/Core.php';
spl_autoload_register(array('Doctrine_Core', 'autoload'));
require_once '../library/TK/db.php';

if(is_dir("/var/www/ral/modules/'.$module.'/models/")){
    Doctrine_Core::setModelsDirectory("/var/www/ral/modules/'.$module.'/models/");
    Doctrine_Core::loadModels('/var/www/ral/modules/'.$module.'/models/');
}
require(BASE_PATH.'/library/TK/init.php');

new init();
error_reporting(0);
ini_set('display_errors', 0);
ini_set("log_errors", 1);
ini_set("error_log", BASE_PATH."/logs/php-error.log");
 
 if(is_dir(BASE_PATH.'/modules/'.$module.'/controllers'))
     require(BASE_PATH.'/modules/'.$module.'/controllers/AdminController.php');
 $contName = ucfirst($module)."_Admin";
 $controller = new $contName();
 $controller->render($action);
 
 
?>  
