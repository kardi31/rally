<?php

class FriendsService extends Service{
    
    protected $friendsTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new FriendsService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->friendsTable = parent::getTable('user','friends');
    }
    
    public function getFriends($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->friendsTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function authenticate(User_Model_Doctrine_Friends $friends,$password){
        $enteredPassword = TK_Text::encode($password, $friends['salt']);
        if($enteredPassword==$friends['password']):
            $_SESSION['friends'] = serialize($friends);
            $_SESSION['role'] = $friends['role'];
            return true;
        else:
            return false;
        endif;
        
    }
    
    
    public function getAuthenticatedFriends(){
        if(!isset($_SESSION['friends'])):
            return false;
        else:
            return unserialize($_SESSION['friends']);
        endif;
    }
    
    public function inviteUser($friend_id,$user_id){
        $friends = $this->friendsTable->getRecord();
        $friends['user_id'] = $user_id;
        $friends['friend_id'] = $friend_id;
        $friends['accepted'] = 0;
        $friends->save();
        
        return $friends;
    }
    
    public function acceptInviteUser($id,$friend_id){
        $q = $this->friendsTable->createQuery('f');
        $q->select('f.*');
        $q->addWhere("f.id = ?",$id);
        $q->addWhere("f.friend_id = ?",$friend_id);
        $result = $q->fetchOne(array(),Doctrine_Core::HYDRATE_RECORD);
        if($result){
            $result->set('accepted',1);
            $result->save();
        }
        else{
            return false;
        }
    }
    
    public function rejectInviteUser($id,$friend_id){
        $q = $this->friendsTable->createQuery('f');
        $q->select('f.*');
        $q->addWhere("f.id = ?",$id);
        $q->addWhere("f.friend_id = ?",$friend_id);
        $result = $q->fetchOne(array(),Doctrine_Core::HYDRATE_RECORD);
        if($result){
            $result->delete();
        }
        else{
            return false;
        }
    }
    
    public function checkFriendInvited($friend_id,$user_id){
        $q = $this->friendsTable->createQuery('f');
        $q->select('f.*');
        $q->addWhere("f.user_id = ? and f.friend_id = ?",array($user_id,$friend_id));
        $q->orWhere("f.user_id = ? and f.friend_id = ?",array($friend_id,$user_id));
        $result = $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
        if($result){
            if($result['accepted']){
                return 'accepted';
            }
            else{
                return 'invited';
            }
        }
        else{
            return false;
        }
    }
    
    public function logout(){
        unset($_SESSION['friends']);
        unset($_SESSION['role']);
        return true;
    }
    
    public function addPremium($friends,$premium){
        if(!$friends instanceof Friends_Model_Doctrine_Friends){
            $friends = $this->getFriends($friends);
        }
	$currentPremium = $friends->get('premium');
	$newPremium = (int)$currentPremium+(int)$premium;
	$friends->set('premium',$newPremium);
	$friends->save();
	return $friends;
    }
    
    public function getUserFriends($user_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->friendsTable->createQuery('f');
        $q->leftJoin('f.UserFriends uf');
        $q->leftJoin('uf.Team t');
        $q->leftJoin('f.User u');
        $q->addWhere('u.active = 1');
        $q->addWhere('uf.active = 1');
        $q->leftJoin('u.Team t2');
        $q->select('uf.*,f.*,t.id,u.id,u.username,t2.id'); 
        $q->addWhere('f.accepted = 1');
        $q->addWhere("f.user_id = ? or f.friend_id = ?",array($user_id,$user_id));
        return $q->execute(array(),$hydrationMode);
    }
    
}
?>
