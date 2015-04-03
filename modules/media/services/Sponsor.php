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
    
    public function getSponsor($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->sponsorTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function saveSponsor($values){
        if(!$sponsor = $this->getSponsor($values['id'])}{
            $sponsor = $this->sponsorTable->getRecord();
        }
        
        $sponsor->fromArray($values);
        $sponsor->save();
        
        return $sponsor;
    }
    
   
}
?>
