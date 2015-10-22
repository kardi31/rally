<?php

class CardService extends Service{
    
    protected $cardTable;
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new CardService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->cardTable = parent::getTable('card','card');
    }
    
    
    public function getCard($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->cardTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getAllCategories($hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->categoryTable->findAll($hydrationMode);
    }
    
    public function getUserCards($user_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->cardTable->createQuery('c');
        $q->select('id,car_model_id');
        $q->addWhere('c.user_id = ?',$user_id);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getFullUserCards($user_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->cardTable->createQuery('c');
        $q->leftJoin('c.Model cm');
        $q->addSelect('c.*,cm.*');
        $q->addWhere('c.user_id = ?',$user_id);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function countLockedCards($user_id){
        $q = $this->cardTable->createQuery('c');
        $q->addSelect('c.*');
        $q->addWhere('c.user_id = ?',$user_id);
        $q->addWhere('c.locked = 1');
        return $q->count();
    }
}
    
?>
