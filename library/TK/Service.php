<?php
 
class Service{
         
    
    protected static $first_names = array(
	'Jan','John','James','Jack','Seb','Marc','Tom','Antoine','Steph','Kevin','Bubba','Andrew','Jean'
    );
    
    protected static $last_names = array(
	'Kowalski','Nowak','Smith','Simpson','House','Thompson','Exum','Evans','Curry','Ripper'
    );
    
    public function __construct(){
       
    }
    
    
    
    public function getTable($module,$tableName) {
        
        $class = ucfirst($module)."_Model_Doctrine_".ucfirst($tableName);
        if(!Doctrine_Core::getLoadedModels($class)):
            Doctrine_Core::loadModels(BASE_PATH.'/modules/'.$module.'/models/');
        endif;
        return Doctrine_Core::getTable($class);
        
    }
    
    public static function loadModels($module,$tableName) {
        
        $class = ucfirst($module)."_Model_Doctrine_".ucfirst($tableName);
        if(!Doctrine_Core::getLoadedModels($class)):
            Doctrine_Core::loadModels(BASE_PATH.'/modules/'.$module.'/models/');
        endif;        
    }
    
    function generateRandomString() {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = rand(4,10);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        ucfirst(strtolower($randomString));
        return $randomString;
    }
    
    function generateRandomPeopleFirstName() {
	$namesCount = count(self::$first_names);
        $nameIndex = rand(0,$namesCount-1);
        $name = self::$first_names[$nameIndex];
        return $name;
    }
    
    function generateRandomPeopleLastName() {
	$namesCount = count(self::$last_names);
        $nameIndex = rand(0,$namesCount-1);
        $name = self::$last_names[$nameIndex];
        return $name;
    }
}