<?php

class DuplicateService extends Service{
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new DuplicateService();
       return self::$instance;
    }
    
    protected $peopleDuplicateTable;
    
    public function __construct(){
        $this->peopleDuplicateTable = parent::getTable('market','peopleDuplicate');
        $this->carDuplicateTable = parent::getTable('market','carDuplicate');
    }
    
    public function getOffer($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->offerTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getDuplicatePeople($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->peopleDuplicateTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getFullOffer($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->offerTable->createQuery('o');
        $q->leftJoin('o.Bids b');
        $q->addWhere('o.'.$field." = ?",$id);
        $q->orderBy('b.value DESC');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    
    public function savePeopleDuplicate($offer_id,$bid_id){
        
        $data = array();
        $data['offer_id'] = $offer_id;
        $data['bid_id'] = $bid_id;
        $record = $this->peopleDuplicateTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        
        return $record;
    }
    
    public function saveCarDuplicate($offer_id,$bid_id){
        
        $data = array();
        $data['offer_id'] = $offer_id;
        $data['bid_id'] = $bid_id;
        $record = $this->carDuplicateTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        
        return $record;
    }
}
?>
