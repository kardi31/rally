<?php

class CardService extends Service{
    
    protected $cardTable;
    protected $packageTable;
    private static $instance = NULL;

    protected $goldCardPrices = array(
        1 => 10,
        3 => 27,
        5 => 45
    );
    
    protected $cardPrices = array(
        1 => 10,
        3 => 27,
        5 => 45
    );
    
    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new CardService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->cardTable = parent::getTable('card','card');
        $this->packageTable = parent::getTable('card','package');
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
    
    
    public function getLockedUserCards($user_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->cardTable->createQuery('c');
        $q->leftJoin('c.Model cm');
        $q->addSelect('c.*,cm.*');
        $q->addWhere('c.locked = 1');
        $q->addWhere('c.user_id = ?',$user_id);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getUnlockedUserCards($user_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->cardTable->createQuery('c');
        $q->leftJoin('c.Model cm');
        $q->addSelect('c.*,cm.*');
        $q->addWhere('c.locked = 0');
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
    
    public function getLockedUserCardsSameType($user_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->cardTable->createQuery('c');
        $q->leftJoin('c.Model cm');
        $q->addSelect('c.*,cm.*');
        $q->addWhere('c.locked = 1');
        $q->addWhere('c.user_id = ?',$user_id);
        $q->groupBy('cm.id');
        $q->having('count(cm.id) > 6');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function checkLockedUserCardsSameType($user_id,$model_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->cardTable->createQuery('c');
        $q->leftJoin('c.Model cm');
        $q->addSelect('c.*,cm.*');
        $q->addWhere('c.locked = 1');
        $q->addWhere('c.user_id = ?',$user_id);
        $q->addWhere('cm.id = ?',$model_id);
        $q->groupBy('cm.id');
        $q->having('count(cm.id) > 6');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getCardsToTransform($user_id,$model_id,$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->cardTable->createQuery('c');
        $q->leftJoin('c.Model cm');
        $q->addSelect('c.*,cm.*');
        $q->addWhere('c.locked = 1');
        $q->addWhere('c.user_id = ?',$user_id);
        $q->addWhere('cm.id = ?',$model_id);
        $q->limit(7);
        return $q->execute(array(),$hydrationMode);
    }
    
    public function createRandomCards($user_id,$amount = 7){
        $models = CarService::getInstance()->getRandomModelIds($amount);
        $modelIds = array();
        foreach($models as $model){
            $cardArray = array();
            $cardArray['user_id'] = $user_id;
            $cardArray['car_model_id'] = $model['id'];
            $cardArray['locked'] = 0;
            $card = $this->cardTable->getRecord();
            $card->fromArray($cardArray);
            $card->save();
            $modelIds[] = $cardArray['car_model_id'];
        }
        return $modelIds;
    }
    
    public function checkCardPrice($amount,$gold = false){
        if($gold){
            $prices = $this->goldCardPrices;
        }
        else{
            $prices = $this->cardPrices;
        }
        
        if(in_array($amount,array_keys($prices))){
            return $prices[$amount];
        }
        
        return false;
        
    }
    
    public function buyPackage($user_id,$amount){
        
        $modelIds = $this->createRandomCards($user_id,$amount);
        
        $packageRow = $this->packageTable->getRecord();
        
        $packageData['model_ids'] = implode(',',$modelIds);
        $packageData['user_id'] = $user_id;
        $packageData['amount'] = $amount;
        
        $packageRow->fromArray($packageData);
        $packageRow->save();
        
        return $packageRow;
    }
    
    
    public function getPackage($id,$user_id,$hydrationMode = Doctrine_Core::HYDRATE_ARRAY){
        $q = $this->packageTable->createQuery('p');
        $q->addSelect('p.*');
        $q->addWhere('p.id = ?',$id);
        $q->addWhere('p.user_id = ?',$user_id);
        return $q->fetchOne(array(),$hydrationMode);
    
    }
}
    
?>
