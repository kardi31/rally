<?php

class UserService extends Service{
    
    protected $userTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new UserService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->userTable = parent::getTable('user','user');
    }
    
    public function getUser($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->userTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function authenticate(User_Model_Doctrine_User $user,$password){
        $enteredPassword = TK_Text::encode($password, $user['salt']);
        if($enteredPassword==$user['password']):
            $_SESSION['user'] = serialize($user);
            $_SESSION['role'] = $user['role'];
            return true;
        else:
            return false;
        endif;
        
    }
    
    
    public function getAuthenticatedUser(){
        if(!isset($_SESSION['user'])):
            return false;
        else:
            return unserialize($_SESSION['user']);
        endif;
    }
    
    public function saveUserFromArray($values,$active = null){
        $user = $this->userTable->getRecord();
        if(!$active)
            $values['active'] = 0;
        $user->fromArray($values);
        $user->save();
        
        return $user;
    }
    
    public function logout(){
        unset($_SESSION['user']);
        unset($_SESSION['role']);
        return true;
    }
    
}
?>
