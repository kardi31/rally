<?php

class MessageService extends Service{
    
    protected $boardTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new MessageService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->boardTable = parent::getTable('user','board');
    }
    
    public function addMessage($user_id,$writer_id,$values){
        $message = $this->boardTable->getRecord();
        $message->set('user_id',$user_id);
        $message->set('writer_id',$writer_id);
        $message->set('message',$values['content']);
        $message->save();
        
        return $message;
    }
    
    public function getUserFriends($user_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->boardTable->createQuery('f');
        $q->leftJoin('f.UserFriends uf');
        $q->select('uf.*,f.*'); 
        $q->addWhere('f.accepted = 1');
        $q->addWhere("f.user_id = ? or f.friend_id = ?",array($user_id,$user_id));
        return $q->execute(array(),$hydrationMode);
    }
    
}
?>
