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
    
    public function getUserInvites($user_id){
        $q = $this->inviteTable->createQuery('i');
        $q->select('i.*');
        $q->addWhere("i.user_id = ?",$user_id);
        return $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
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
