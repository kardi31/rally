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
            $_SESSION['start'] = time(); // Taking now logged in time.
            // Ending a session in 15 minutes from the starting time.
            $_SESSION['expire'] = $_SESSION['start'] + (60 * 60);
        
            $_SESSION['user'] = serialize($user);
            $_SESSION['role'] = $user['role'];
            return true;
        else:
            return false;
        endif;
    }
    
    public function quickAuthenticate(User_Model_Doctrine_User $user){
        
            $_SESSION['start'] = time(); // Taking now logged in time.
            // Ending a session in 60 minutes from the starting time.
            $_SESSION['expire'] = $_SESSION['start'] + (60 * 60);
        
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
        // if session expired, redirect to login page
        // 15 mins for session
        if(isset($_SESSION['expire'])&&($_SESSION['expire']< time()&&!isset($_COOKIE['siteAuth']))){
            $this->logout();
            return false;
        }
        
        
//        var_dump($_COOKIE);exit;
        if(!isset($_SESSION['user'])):
            // if user is not logged but cookie to remember user is set
            // then automatically log in user
            if(isset($_COOKIE['siteAuth'])){
//                echo "in";exit;
                if($user = $this->getUser($_COOKIE['siteAuth'],'username')){
                    $this->quickAuthenticate($user);
                    return $user;
                }
            }
            return false;
        else:
            // extend session
            $_SESSION['start'] = time(); // Taking now logged in time.
            // Ending a session in 15 minutes from the starting time.
            $_SESSION['expire'] = $_SESSION['start'] + (60 * 60);
            
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
        $q->addWhere("LOWER(u.username) like ?",strtolower($query)."%");
        $q->addWhere('u.active = 1');
        $q->limit(4);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getReminderUser($username,$email,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->userTable->createQuery('u');
        $q->addWhere("LOWER(u.username) like ?",strtolower($username)."%");
        $q->addWhere("LOWER(u.email) like ?",strtolower($email)."%");
        $q->addWhere('u.active = 1');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getNewPasswordUser($id,$token,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->userTable->createQuery('u');
        $q->addWhere("u.token like ?",$token);
        $q->addWhere("u.id = ?",$id);
        $q->addWhere('u.active = 1');
        return $q->fetchOne(array(),$hydrationMode);
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
        $q->addWhere("LOWER(u.username) like ?",$username."%");
        $q->orderBy('u.username ASC');
        $q->addWhere('u.active = 1');
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
    
    public function addGoldMembership($user,$amount){
        $expireDate = $user['gold_member_expire'];
        if(strlen($expireDate)&&$expireDate>=date('Y-m-d H:i:s')){
            $newDate = new DateTime($expireDate);
            $newDate->add(new DateInterval('P'.$amount.'D'));
            $user->set('gold_member',1);
            $user->set('gold_member_expire',$newDate->format('Y-m-d H:i:s'));
            $user->save();
            
            
        }
        else{
            $user->set('gold_member',1);
            $user->set('gold_member_expire',date('Y-m-d H:i:s',strtotime('+ '.$amount.' days')));
            $user->save();
        }
        
        if($this->getAuthenticatedUser()){
            $this->refreshAuthentication();
        }
    }
    
    
    public function getUsersWithReferer($user_id){
        $q = $this->userTable->createQuery('u');
        $q->select('u.referer,u.referer_paid,u.email,u.username,u.email,u.created_at');
        $q->addWhere("u.referer = ?",$user_id);
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    }
    
    
    public function getTopCardList($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->userTable->createQuery('u');
        $q->orderBy('u.card_rank DESC');
        $q->limit(100);
	return $q->execute(array(),$hydrationMode);
    }
}
?>
