<?php
require_once 'library/Doctrine/Core.php';
spl_autoload_register(array('Doctrine_Core', 'autoload'));
$manager = Doctrine_Manager::getInstance();

?>
<?php 
 $args = $_SERVER["argv"];  
 $moduleName = $args[2];
$connection["library"] =
  array(
    "version"=> "1.2.3",
    "compiled"=> ""
  );
  $connection["connection_string"]= "mysql://root:@localhost/ral";
  $connection["connection_name"]= "doctrine";
  $connection["yaml_schema_path"]= 'C:/xampp2/htdocs/ral/modules/'.$moduleName.'/data/schema';
  $connection["generate_models_options"]=
  array(
    "pearStyle"=> false,
    "classPrefix"=> ucfirst($moduleName)."_Model_Doctrine_",
    "classPrefixFiles"=> false,
    "generateBaseClasses"=> true,
    "baseClassesDirectory"=> ".",
    "baseClassPrefix"=> "Base",
    "generateTableClasses"=> true,
    "phpDocName"=> "Tomasz",
    "phpDocEmail"=>"kardi31@o2.pl"
  );
  $connection["autoload"]= 
  array(
    "autoload"=> "1",
    "modelsAutoload"=>"1",
    "extensionsAutoload"=>"1"
  );
  $connection["attribute"]=
  array(
    "ATTR_DEFAULT_TABLE_COLLATE"=>"utf8_general_ci",
    "ATTR_DEFAULT_TABLE_CHARSET"=>"utf8",
    "ATTR_DEFAULT_TABLE_TYPE"=>"MyISAM",
    "ATTR_USE_DQL_CALLBACKS"=>"1",
    "ATTR_AUTO_ACCESSOR_OVERRIDE"=>"",
    "ATTR_AUTOLOAD_TABLE_CLASSES"=> "1",
    "ATTR_VALIDATE"=>"Doctrine::VALIDATE_ALL",
    "ATTR_EXPORT"=>"Doctrine::EXPORT_ALL",
    "ATTR_MODEL_LOADING"=>"Doctrine_Core::MODEL_LOADING_CONSERVATIVE",
    "ATTR_AUTO_FREE_QUERY_OBJECTS"=>"1",
    "ATTR_PORTABILITY"=>"Doctrine_Core::PORTABILITY_NONE",
    "ATTR_MODEL_CLASS_PREFIX"=>"Model_",
    "ATTR_QUOTE_IDENTIFIER"=>"1"
  );
 $conn = Doctrine_Manager::connection('mysql://root:@localhost/ral', 'connection ');
 $dir = __DIR__;
   $modelDir = scandir($dir . '/modules/');
   foreach($modelDir as $module):
       if(strlen($module)>2){
       Doctrine_Core::loadModels("C:/xampp2/htdocs/ral/modules/team/models");
       Doctrine_Core::loadModels("C:/xampp2/htdocs/ral/modules/people/models");
       Doctrine_Core::loadModels("C:/xampp2/htdocs/ral/modules/car/models");
       Doctrine_Core::loadModels("C:/xampp2/htdocs/ral/modules/rally/models");
       Doctrine_Core::loadModels("C:/xampp2/htdocs/ral/modules/league/models");
       Doctrine_Core::loadModels("C:/xampp2/htdocs/ral/modules/user/models");
       }
   endforeach;
   
  $connection["models_path"] = "C:/xampp2/htdocs/ral/modules/".$moduleName."/models";

  $cli = new Doctrine_Cli($connection); 
  
$cli->run($args);
?>