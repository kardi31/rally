<?php

class LoginService extends Service{
    
    protected $loginTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new LoginService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->loginTable = parent::getTable('user','login');
    }
    
//    public function getNotification($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
//        return $this->loginTable->findOneBy($field,$id,$hydrationMode);
//    }
    
    
    public function saveLoginFromArray($user_id,$valid = true){
        $login = $this->loginTable->getRecord();
        
        $loginArray = array();
        $loginArray['user_id'] = $user_id;
        
        $loginArray['ip'] = TK_Helper::getRealIpAddr();
        $loginArray['hostname'] = gethostbyaddr($loginArray['ip']);
        $loginArray['valid'] = (int)$valid;
        
        $login->fromArray($loginArray);
        $login->save();
        
        return $login;
    }
    
   
    
}
?>
