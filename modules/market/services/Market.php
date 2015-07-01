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
    
    public function getBid($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->bidTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getCarBid($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->carBidTable->findOneBy($field,$id,$hydrationMode);
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
    
    public function getFinishedOffersNotMovedNoBid($hydrationMode=Doctrine_Core::HYDRATE_RECORD){
        $q = $this->offerTable->createQuery('o');
//        $q->leftJoin('o.Bids b');
        $q->addWhere('(o.highest_bid = 0 or o.active = 0)');
        $q->addWhere('o.player_moved = 0');
        $q->addWhere('o.finish_date < NOW()');
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getFinishedOffersNotMoved($hydrationMode=Doctrine_Core::HYDRATE_RECORD){
        $q = $this->offerTable->createQuery('o');
        $q->innerJoin('o.Bids b');
        $q->addWhere('o.highest_bid > 0 and o.active = 1');
        $q->addWhere('o.player_moved = 0');
        $q->addWhere('o.finish_date < NOW()');
        $q->orderBy('b.value');
        return $q->execute(array(),$hydrationMode);
    }
    
    public function getFullOfferAndBid($id,$hydrationMode=Doctrine_Core::HYDRATE_RECORD){
        $q = $this->offerTable->createQuery('o');
        $q->innerJoin('o.Bids b');
        $q->where('o.id = ?',$id);
        $q->orderBy('b.value');
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
    
    public function calculateHighestBid($offer_id){
        $offer = $this->getOffer($offer_id);
        $q = $this->bidTable->createQuery('b');
        $q->addWhere('b.offer_id = ?',$offer_id);
        $q->addWhere('b.active = 1');
        $q->orderBy('b.value DESC');
        $q->limit(1);
        $result = $q->fetchOne(array(),Doctrine_Core::HYDRATE_ARRAY);
        if(!$result){
            $offer->highest_bid = 0;
        }
        else{
            $offer->highest_bid = $result['value'];
        }
        $offer->save();
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
        $data['finish_date'] = date('Y-m-d H:i:s', strtotime('+ '.($values['days']+1).' days'));
        $data['asking_price'] = $values['asking_price'];
        $data['highest_bid'] = 0;
        $data['team_id'] = $values['team_id'];
        $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
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
        $data['team_id'] = $values['team_id'];
        $data['highest_bid'] = 0;
        if($this->checkIfCarOnMarket($car['id'])!=0){
            return array('status' => false,'message' => 'car+on+market');
        }
        
        if(!TeamService::getInstance()->canAfford($values['team_id'],$values['selling_fee'])){
            return array('status' => false,'message' => 'no+cash');
        }
        
        $record = $this->carOfferTable->getRecord();
        $record->fromArray($data);
        $record->save();
        $teamService->removeTeamMoney($car['Team']['id'],$values['selling_fee'],6,'Putting car '.$car['name'].' on market');
        $car->set('on_market',1);
        $car->save();
        return array('status' => true, 'element' => $record);
    }
    
    public function getAllActiveOffers(){
        $q = $this->offerTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.active = 1');
        $q->addWhere('o.finish_date > NOW()');
        $q->orderBy('o.finish_date,b.active DESC,b.value DESC');
        return $q->fetchArray();
    }
    
    public function getAllActiveMyPlayers($team_id){
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
    
    public function getAllActiveMyPlayerOffers($team_id){
        $q = $this->offerTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('b.team_id = ?',$team_id);
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    public function getAllActiveMyOffers($team_id){
        $q = $this->offerTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('b.team_id = ?',$team_id);
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
        $q->addWHere('o.active = 1');
        $q->addWhere('o.finish_date > NOW()');
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    public function canAfford($team,$bid){
        $team_id = $team['id'];
        $q = $this->offerTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->addWhere('b.team_id = ?',$team_id);
        $q->addWhere('b.value = o.highest_bid');
        $q->select('sum(b.value) as bids_value');
        $q->addWhere('o.player_moved = 0');
        $q->addWhere('o.active = 1');
        $q->addWhere('o.canceled = 0');
        $q->groupBy('b.team_id');
        $playerOfferResult = $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        
        
        $q = $this->carOfferTable->createQuery('o');
        $q->select('o.*,b.*,t.name,p.*,bt.name');
        $q->leftJoin('o.Bids b');
        $q->addWhere('b.team_id = ?',$team_id);
        $q->addWhere('b.value = o.highest_bid');
        $q->select('sum(b.value) as bids_value');
        $q->addWhere('o.car_moved = 0');
        $q->addWhere('o.active = 1');
        $q->addWhere('o.canceled = 0');
        $q->groupBy('b.team_id');
        $carOfferResult = $q->fetchOne(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        $remainingTeamFinances = $team['cash']-(int)$playerOfferResult-(int)$carOfferResult;
        
        if($bid>$remainingTeamFinances)
            return false;
        else
            return true;
    }
    
    public function getAllActiveMyCars($team_id){
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
    
    
    public function getAllActiveMyCarOffers($team_id){
        $q = $this->carOfferTable->createQuery('o');
        $q->select('o.*,b.*,t.name,c.*,bt.name,m.*');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('o.Car c');
        $q->leftJoin('c.Team t');
        $q->leftJoin('c.Model m');
        $q->leftJoin('b.Team bt');
        $q->addWhere('o.finish_date > NOW()');
        $q->addWhere('b.team_id = ?',$team_id);
        $q->orderBy('o.finish_date,b.value DESC');
        return $q->fetchArray();
    }
    
    public function bidOffer($values,Market_Model_Doctrine_Offer $offer,$team){
        $team_id = $team['id'];
        $data = array();
        $data['offer_id'] = $offer['id'];
        $data['value'] = $values['bid'];
        $data['team_id'] = $team_id;
        $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
        
        
        if(!$this->canAfford($team,$values['bid'])){
            return array('status' => false,'message' => 'not enough money');
        }
        
        if($this->checkIfPlayerOnMarket($offer['Player']['id'])==0){
            return array('status' => false,'message' => 'finished');
        }
        
        if($this->checkIfHoldHighestBid($offer['id'],$team_id)){
            return array('status' => false,'message' => 'already highest');
        }
        
        if($this->anyHigherBids($offer['id'],$values['bid'])){
            return array('status' => false,'message' => 'not highest');
        }
        
        
        // make sure bid > than asking price
        if($values['bid']<$offer['asking_price']){
            return array('status' => false,'message' => 'greater than asking');
        }
        $record = $this->bidTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        $offer->set('highest_bid',$values['bid']);
        $offer->save();
        
        return array('status' => 'success', 'element' => $record);
    }
    
    public function bidCarOffer($values,Market_Model_Doctrine_CarOffer $offer,$team){
        $team_id = $team['id'];
        $data = array();
        $data['offer_id'] = $offer['id'];
        $data['value'] = $values['bid'];
        $data['team_id'] = $team_id;
        $data['user_ip'] = $_SERVER['REMOTE_ADDR'];
        
        
        if(!$this->canAfford($team,$values['bid'])){
            return array('status' => false,'message' => 'not enough money');
        }
        
        if($this->checkIfCarOnMarket($offer['Car']['id'])==0){
            return array('status' => false,'message' => 'finished');
        }
        
        if($this->checkIfHoldHighestBidCar($offer['id'],$team_id)){
            return array('status' => false,'message' => 'already highest');
        }
        
        if($this->anyHigherBidsCar($offer['id'],$values['bid'])){
            return array('status' => false,'message' => 'not highest');
        }
        
        
        // make sure bid > than asking price
        if($values['bid']<$offer['asking_price']){
            return array('status' => false,'message' => 'greater than asking');
        }
        
        
        
        $record = $this->carBidTable->getRecord();
        $record->fromArray($data);
        $record->save();
        
        $offer->set('highest_bid',$values['bid']);
        $offer->save();
        
        return array('status' => 'success', 'element' => $record);
    }
    
}
?>
