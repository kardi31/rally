<?php

class UserService extends Service{
    
    protected $userTable;
    protected $premiumLogTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new UserService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->userTable = parent::getTable('user','user');
        $this->premiumLogTable = parent::getTable('user','premiumLog');
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
    
    public function quickAuthenticate(User_Model_Doctrine_User $user){
        
            if($user['gold_member']==1&&$user['gold_member_expire']<date('Y-m-d H:i:s')){
                $user->set('gold_member',0);
                $user->save();
                $_SESSION['user'] = serialize($user);
                $_SESSION['role'] = $user['role'];
            }
            else{
                $_SESSION['user'] = serialize($user);
                $_SESSION['role'] = $user['role'];
            }
            View::getInstance()->assign('authenticatedUser',$user);
            return true;
    }
    
    public function refreshAuthentication(){
        $user = $this->getAuthenticatedUser();
        $newUser = $this->getUser($user['id']);
        $_SESSION['user'] = serialize($newUser);
        $_SESSION['role'] = $newUser['role'];
    }
    
    
    public function getAuthenticatedUser(){
        if(!isset($_SESSION['user'])):
            return false;
        else:
            $user = unserialize($_SESSION['user']);
            if($user['gold_member']==1&&$user['gold_member_expire']<date('Y-m-d H:i:s')){
                $user->set('gold_member',0);
                $user->save();
                $_SESSION['user'] = serialize($user);
                $_SESSION['role'] = $user['role'];
            }
            return $user;
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
    
    public function addPremium($user,$premium,$description = false){
        if(!$user instanceof User_Model_Doctrine_User){
            $user = $this->getUser($user);
        }
	$currentPremium = $user->get('premium');
	$newPremium = (int)$currentPremium+(int)$premium;
	$user->set('premium',$newPremium);
	$user->save();
        if($description){
            $this->addPremiumLog($user,$premium,$description);
        }
        // to display properlyu premium
        // premium is caught from session which we must refresh
        if($this->getAuthenticatedUser())
            $this->refreshAuthentication();
	return $user;
    }
    
    public function removePremium($user,$premium,$description = false){
        if(!$user instanceof User_Model_Doctrine_User){
            $user = $this->getUser($user);
        }
	$currentPremium = $user->get('premium');
	$newPremium = (int)$currentPremium-(int)$premium;
	$user->set('premium',$newPremium);
	$user->save();
        
        if($description){
            $this->removePremiumLog($user,$premium,$description);
        }
        if($this->getAuthenticatedUser())
            $this->refreshAuthentication();
	return $user;
    }
    
    public function addPremiumLog($user,$amount,$description){
        $premiumLog = $this->premiumLogTable->getRecord();
        $premiumLog->amount = $amount;
        $premiumLog->income = 1;
        $premiumLog->user_id = $user['id'];
        $premiumLog->description = $description;
        $premiumLog->save();
    }
    
    
    public function removePremiumLog($user,$amount,$description){
        $premiumLog = $this->premiumLogTable->getRecord();
        $premiumLog->amount = $amount;
        $premiumLog->income = 0;
        $premiumLog->user_id = $user['id'];
        $premiumLog->description = $description;
        $premiumLog->save();
    }
    
    public function findUsers($query,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->userTable->createQuery('u');
        $q->select('u.username');
        $q->addWhere("u.username like ?",$query."%");
        $q->limit(4);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getUsersWithRefererNotPaid($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->userTable->createQuery('u');
        $q->select('u.*');
        $q->addWhere('u.referer IS NOT NULL');
        $q->addWhere('u.referer_paid = 0');
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
