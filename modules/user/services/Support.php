<?php

class SupportService extends Service{
    
    protected $supportTable;
    private static $instance = NULL;

    protected $supportCategories = array(
            'general' => 'General query',
            'payment' => 'Payments',
            'report-cheater' => 'Report cheater',
            'report-bug' => 'Report bug'
        );
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new SupportService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->supportTable = parent::getTable('user','support');
    }
    
    public function getCategory($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->supportTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function checkLastUserSupportEnquiry($user){
        $q = $this->supportTable->createQuery('p');
        $q->addWhere('p.user_id = ?',$user['id']);
        $q->orderBy('p.created_at DESC');
        $result = $q->fetchOne();
        if(!$result)
            return true;
        
        if($result['created_at']>date('Y-m-d H:i:s',strtotime('-30 seconds'))){
            return false;   
        }
        return true;
    }
    
    public function addSupportEnquiry($values,$user_id){
        $enquiry = $this->supportTable->getRecord();
        $values['user_id'] = $user_id;
        $enquiry->fromArray($values);
        $enquiry->save();
        
        return $enquiry;
    }
    
    public function getSupportCategory($category){
        return $this->supportCategories[$category];
    }
    
    public function getSupportCategories(){
        return $this->supportCategories;
    }
}
    
?>
