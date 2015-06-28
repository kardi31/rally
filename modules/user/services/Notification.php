<?php

class NotificationService extends Service{
    
    protected $notificationTable;
    
    private static $types = array(
        1 => 'Friendly league'
    );
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new NotificationService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->notificationTable = parent::getTable('user','notification');
    }
    
    public function getNotification($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->notificationTable->findOneBy($field,$id,$hydrationMode);
    }
    
    
    public function saveNotificationFromArray($email,$user_id){
        $notification = $this->notificationTable->getRecord();
        $notification['user_id'] = $user_id;
        $notification['email'] = $email;
        $notification->save();
        
        return $notification;
    }
    
    public function checkEmailNotificationd($email,$user_id){
        $q = $this->notificationTable->createQuery('f');
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
    
    
    public function addNotification($text,$type,$user_id,$link = false){
        $data = array();
        $data['message'] = $text;
        $data['type'] = $type;
        $data['user_id'] = $user_id;
        if($link)
            $data['link'] = $link;
        $notification = $this->notificationTable->getRecord();
        $notification->fromArray($data);
	$notification->save();
	return $notification;
    }
    
    public function getFullNotification($id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->notificationTable->createQuery('i');
        $q->leftJoin('i.User ui');
        $q->select('ui.*,i.*'); 
        $q->addWhere('i.id = ?',$id);
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getAllUserNotifications($user_id,$limit = false,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->notificationTable->createQuery('n');
        $q->select('n.*'); 
        $q->addWhere('n.user_id = ?',$user_id);
        if($limit)
            $q->limit($limit);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function removeNotification($id){
        $notification = $this->getNotification($id);
        $notification->delete();
    }
    
}
?>
