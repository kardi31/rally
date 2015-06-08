<?php

class InviteService extends Service{
    
    protected $inviteTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new InviteService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->inviteTable = parent::getTable('user','invite');
    }
    
    public function getInvite($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->inviteTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function authenticate(User_Model_Doctrine_Invite $invite,$password){
        $enteredPassword = TK_Text::encode($password, $invite['salt']);
        if($enteredPassword==$invite['password']):
            $_SESSION['invite'] = serialize($invite);
            $_SESSION['role'] = $invite['role'];
            return true;
        else:
            return false;
        endif;
        
    }
    
    
    
    public function saveInviteFromArray($email,$user_id){
        $invite = $this->inviteTable->getRecord();
        $invite['user_id'] = $user_id;
        $invite['email'] = $email;
        $invite->save();
        
        return $invite;
    }
    
    public function checkEmailInvited($email,$user_id){
        $q = $this->inviteTable->createQuery('f');
        $q->select('f.*');
        $q->addWhere("f.user_id = ?",$user_id);
        $q->addWhere("f.email like ?",$email);
        $result = $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
        if($result){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function logout(){
        unset($_SESSION['invite']);
        unset($_SESSION['role']);
        return true;
    }
    
    public function addPremium($invite,$premium){
        if(!$invite instanceof Invite_Model_Doctrine_Invite){
            $invite = $this->getInvite($invite);
        }
	$currentPremium = $invite->get('premium');
	$newPremium = (int)$currentPremium+(int)$premium;
	$invite->set('premium',$newPremium);
	$invite->save();
	return $invite;
    }
    
    public function getFullInvite($id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->inviteTable->createQuery('i');
        $q->leftJoin('i.User ui');
        $q->select('ui.*,i.*'); 
        $q->addWhere('i.id = ?',$id);
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function removeInvite($id){
        $invite = $this->getInvite($id);
        $invite->delete();
    }
    
}
?>
