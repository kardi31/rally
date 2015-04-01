<?php

class SponsorService extends Service{
    
    protected $sponsorTable;
    private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new SponsorService();
       return self::$instance;
    }
    
   
    public function __construct(){
        $this->sponsorTable = parent::getTable('team','sponsorList');
    }
    
    public function getAllTeams(){
        return $this->sponsorTable->findAll();
    }
    
    public function getTeam($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->sponsorTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function createRandomTeam($values,$user_id = null){
        if($user_id)
            $values['name'] = "Team_".$user_id;
        else
            $values['name'] = $this->generateRandomString();
        
        $record = $this->sponsorTable->getRecord();
        $record->fromArray($values);
        $record->save();
        
        return $record;
    }
    
   
}
?>
