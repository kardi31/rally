<?php

class MarketService extends Service{
    
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new MarketService();
       return self::$instance;
    }
    
    protected $offerTable;
    protected $bidTable;
    protected $carOfferTable;
    protected $carBidTable;
    
    public function __construct(){
        $this->offerTable = parent::getTable('market','offer');
        $this->bidTable = parent::getTable('market','bid');
        $this->carOfferTable = parent::getTable('market','carOffer');
        $this->carBidTable = parent::getTable('market','carBid');
    }
    
    public function getOffer($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->offerTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getCarOffer($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->carOfferTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getFullOffer($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->offerTable->createQuery('o');
        $q->leftJoin('o.Bids b');
        $q->addWhere('o.'.$field." = ?",$id);
        $q->orderBy('b.value DESC');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getFullCarOffer($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        $q = $this->carOfferTable->createQuery('o');
        $q->leftJoin('o.Bids b');
        $q->addWhere('o.'.$field." = ?",$id);
        $q->orderBy('b.value DESC');
        return $q->fetchOne(array(),$hydrationMode);
    }
    
    public function getAllDrivers(){
        return $this->offerTable->findAll();
    }
    
    public function checkIfCarOnMarket($car_id){
        $q = $this->carOfferTable->createQuery('o');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('o.car_id = ?',$car_id);
        return $q->count();
    }
    
    public function checkIfPlayerOnMarket($player_id){
        $q = $this->offerTable->createQuery('o');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('o.people_id = ?',$player_id);
        return $q->count();
    }
    
    public function checkIfHoldHighestBid($offer_id,$team_id){
        $q = $this->bidTable->createQuery('b');
        $q->addWhere('b.offer_id = ?',$offer_id);
        $q->select('b.team_id');
        $q->orderBy('b.value DESC');
        $highestBidTeamId = $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        if($team_id==$highestBidTeamId)
            return true;
        else
            return false;
    }
    
    public function checkIfHoldHighestBidCar($offer_id,$team_id){
        $q = $this->carBidTable->createQuery('b');
        $q->addWhere('b.offer_id = ?',$offer_id);
        $q->select('b.team_id');
        $q->orderBy('b.value DESC');
        $highestBidTeamId = $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        if($team_id==$highestBidTeamId)
            return true;
        else
            return false;
    }
    
    public function anyHigherBids($offer_id,$bid){
        $q = $this->bidTable->createQuery('b');
        $q->addWhere('b.offer_id = ?',$offer_id);
        $q->addWhere('b.value >= ?',$bid);
        $q->orderBy('b.value DESC');
        if($q->count()>0)
            return true;
        else
            return false;
    }
    
    
    public function anyHigherBidsCar($offer_id,$bid){
        $q = $this->carBidTable->createQuery('b');
        $q->addWhere('b.offer_id = ?',$offer_id);
        $q->addWhere('b.value >= ?',$bid);
        $q->orderBy('b.value DESC');
        if($q->count()>0)
            return true;
        else
            return false;
    }
    
    public function addPlayerOnMarket($values,People_Model_Doctrine_People $player){
        $teamService = TeamService::getInstance();
        
        $data = array();
        $data['people_id'] = $player['id'];
        $data['start_date'] = date('Y-m-d H:i:s');
        $data['finish_date'] = date('Y-m-d H:i:s', strtotime('+ '.$values['days'].' days'));
        $data['asking_price'] = $values['asking_price'];
        $data['highest_bid'] = 0;
        if($this->checkIfPlayerOnMarket($player['id'])!=0){
            return false;
        }
        
        $record = $this->offerTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        $teamService->removeTeamMoney($player['Team']['id'],$values['selling_fee'],6,'Putting player '.$player['first_name']." ".$player['last_name'].' on transfer list');
        $player->set('on_market',1);
        $player->save();
        
        return $record;
    }
    
    public function addCarOnMarket($values,Car_Model_Doctrine_Car $car){
        $teamService = TeamService::getInstance();
        
        $data = array();
        $data['car_id'] = $car['id'];
        $data['start_date'] = date('Y-m-d H:i:s');
        $data['finish_date'] = date('Y-m-d H:i:s', strtotime('+ '.$values['days'].' days'));
        $data['asking_price'] = $values['asking_price'];
        $data['highest_bid'] = 0;
        if($this->checkIfCarOnMarket($car['id'])!=0){
            return false;
        }
        
        $record = $this->carOfferTable->getRecord();
        $record->fromArray($data);
        $record->save();
        $teamService->removeTeamMoney($car['Team']['id'],$values['selling_fee'],6,'Putting car '.$car['name'].' on market');
        $car->set('on_market',1);
        $car->save();
        return $record;
    }
    
    public function getAllActiveOffers(){
        $q = $this->offerTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    
    public function getAllActiveMyPlayerOffers($team_id){
        $q = $this->offerTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('p.team_id = ?',$team_id);
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    public function getAllActiveCarOffers(){
        $q = $this->carOfferTable->createQuery('o');
        $q->select('o.*,b.*,t.name,c.*,bt.name,m.*');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Car c');
        $q->leftJoin('c.Team t');
        $q->leftJoin('c.Model m');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    public function getAllActiveMyCarOffers($team_id){
        $q = $this->carOfferTable->createQuery('o');
        $q->select('o.*,b.*,t.name,c.*,bt.name,m.*');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Car c');
        $q->leftJoin('c.Team t');
        $q->leftJoin('c.Model m');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('c.team_id = ?',$team_id);
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    public function bidOffer($values,Market_Model_Doctrine_Offer $offer,$team_id){
        $data = array();
        $data['offer_id'] = $offer['id'];
        $data['value'] = $values['bid'];
        $data['team_id'] = $team_id;
        
        if($this->checkIfPlayerOnMarket($offer['Player']['id'])==0){
            return false;
        }
        
        if($this->checkIfHoldHighestBid($offer['id'],$team_id)){
            return false;
        }
        
        if($this->anyHigherBids($offer['id'],$values['bid'])){
            return false;
        }
        
        $record = $this->bidTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        $offer->set('highest_bid',$values['bid']);
        $offer->save();
        
        return $record;
    }
    
    public function bidCarOffer($values,Market_Model_Doctrine_CarOffer $offer,$team_id){
        $data = array();
        $data['offer_id'] = $offer['id'];
        $data['value'] = $values['bid'];
        $data['team_id'] = $team_id;
        
        if($this->checkIfCarOnMarket($offer['Car']['id'])==0){
            return false;
        }
        
        if($this->checkIfHoldHighestBidCar($offer['id'],$team_id)){
            return false;
        }
        
        if($this->anyHigherBidsCar($offer['id'],$values['bid'])){
            return false;
        }
        
        $record = $this->carBidTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        $offer->set('highest_bid',$values['bid']);
        $offer->save();
        
        return $record;
    }
    
}
?>
