<?php

class SettingsService extends Service{
    
    protected $_settings = array();
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new SettingsService();
       return self::$instance;
    }
    
    public function getSetting($param){
        if(!empty($this->_settings))
            return $this->_settings[$param];
        $ini_array = parse_ini_file(BASE_PATH."/config/config.ini");
        
        if($ini_array)
            $this->_settings = $ini_array;
        
        return $this->_settings[$param];
    }
    
    
   
}
?>
