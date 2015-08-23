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
    
    
    public function getMessage($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->boardTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function addMessage($user_id,$writer_id,$values){
        $message = $this->boardTable->getRecord();
        $message->set('user_id',$user_id);
        $message->set('writer_id',$writer_id);
        $message->set('message',$values['content']);
        $message->save();
        
        return $message;
    }
    
    public function getUserMessages($user_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->boardTable->createQuery('f');
        $q->leftJoin('f.Writer w');
        $q->leftJoin('w.Team t');
        $q->select('w.*,f.*,t.id'); 
        $q->addWhere('f.created_at > ?',date('Y-m-d H:i:s',strtotime('-3 months')));
        $q->addWhere("f.user_id = ?",$user_id);
        return $q->execute(array(),$hydrationMode);
    }
    
    
    public function getUserMessagesNotReaded($user_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->boardTable->createQuery('f');
        $q->addWhere('f.readed = 0');
        $q->addWhere("f.user_id = ?",$user_id);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function setMessagesReaded($user_id){
        $q = $this->boardTable->createQuery();
        $q->update('User_Model_Doctrine_Board f')
                ->set(array('f.readed' => 1,'f.updated_at' => date('Y-m-d H:i:s')));
        $q->addWhere('f.readed = 0');
        $q->addWhere("f.user_id = ?",$user_id);
        $q->execute();
    }
    
    public function checkLastUserMessage($writer_id,$user_id){
        $q = $this->boardTable->createQuery('b');
        $q->addWhere('b.user_id = ?',$user_id);
        $q->addWhere('b.writer_id = ?',$writer_id);
        $q->orderBy('b.created_at DESC');
        $result = $q->fetchOne();
       
        if(!$result)
            return true;
        
        if($result['created_at']>date('Y-m-d H:i:s',strtotime('-30 seconds'))){
            return false;   
        }
        return true;
    }
    
}
?>
