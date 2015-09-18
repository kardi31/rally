<?php
 
class Service{
         
    
    protected static $first_names = array(
	'Adam','Andrew','Anthony','Antoine','Alan',
        'Bubba','Bruno',
        'Christian','Colin','Carlos',
        'Daniel','Diego','David','Dante',
        'Eric','Eduardo','Elias',
        'Fernando','Francis',
        'George','Gabriel',
        'Hans','Harmon',
        'Issac','Ian','Iker',
        'Jack','Jacob', 'James','Jan','Jean','John','Jordan','Julian','Jose',
        'Kevin',
        'Luke','Leo','Luis','Lucas',
        'Marc','Mateo','Matthew','Martin','Max','Mason','Mike','Michael','Matias','Mario',
        'Nicolas',
        'Peter','Paul','Pablo','Pedro',
        'Robert','Richard','Ryan','Russell',
        'Seb','Steph','Sebastian',
        'Thiago','Tom','Tim','Thomas',
        'Victor'
    );
    
    protected static $last_names = array(
        'Adams','Anderson','Allen',
        'Brown','Bailey',
	'Curry','Cooper','Clarke','Carter','Campbell','Cook','Cabrera',
        'Davies',
        'Evans','Exum',
        'Fernandes',
        'Green','Gonzalez','Garcia',
        'House','Harris','Harrison','Hall','Hernandez',
        'Jones','Johnson','Jackson','James',
        'Kowalski','King',
        'Lewis','Lee','Lopez',
        'Martin','Morgan','Morris','Mitchell','Margin','Medina',
        'Nowak',
        'Phillips','Patel','Perez',
        'Ripper','Roberts','Rodriguez','Ramos',
        'Simpson','Smith','Scott','Suarez','Sanchez',
        'Taylor','Thompson','Thomas','Turner',
        'Williams','Wilson','Walker','White','Watson','Wright','Ward',
        'Young'
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
    
    function getPageLimits($elementsPerPage){
        $result = array();
        if(isset($GLOBALS['urlParams']['page'])){
            $page = (int)$GLOBALS['urlParams']['page'];
            $result['page'] = $page;
            $result['offset'] = ($page-1)*$elementsPerPage;
        }
        else{
            $page = 1;
            $result['page'] = $page;
            $result['offset'] = 0;
        }
        
        $result['limit'] = $elementsPerPage;
        
        return $result;
    }
}