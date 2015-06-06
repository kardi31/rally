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
    
    public function addPremium($user,$premium){
        if(!$user instanceof User_Model_Doctrine_User){
            $user = $this->getUser($user);
        }
	$currentPremium = $user->get('premium');
	$newPremium = (int)$currentPremium+(int)$premium;
	$user->set('premium',$newPremium);
	$user->save();
	return $user;
    }
    
    public function findUsers($query,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->userTable->createQuery('u');
        $q->select('u.username');
        $q->addWhere("u.username like ?",$query."%");
        $q->limit(4);
        return $q->execute(array(),$hydrationMode);
    }
    
    
    public function searchForUsers($username,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $username = strtolower($username);
        $q = $this->userTable->createQuery('u');
        $q->leftJoin('u.Team t');
        $q->select('u.username,t.name');
        $q->addWhere("u.username like ?",$username."%");
        $q->orderBy('u.username ASC');
        $q = TK_Paginator::paginate($q,10);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function checkUserPremium($user_id,$amount){
        $user = $this->getUser($user_id);
        if($user['premium']>=$amount)
            return true;
        else
            return false;
    }
}
?>
