<?php

class PremiumService extends Service{
    
    
    protected $premium = array(
        100 => 2,
        315 => 6,
        550 => 10,
        1150 => 20
    );
    
    protected $providers = array(
        'paypal',
        'transferuj'
    );
    
    protected $userTable;
    protected $premiumLogTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new PremiumService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->userTable = parent::getTable('user','user');
        $this->premiumLogTable = parent::getTable('user','premiumLog');
    }
    
    public function getUser($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->userTable->findOneBy($field,$id,$hydrationMode);
    }
    
    public function getPremiumAmounts(){
        return $this->premium;
    }
    
    
    public function getPremiumCost($amount){
        if(isset($this->premium[$amount])){
            return $this->premium[$amount];
        }
        return 20;
    }
    
    public function getProviders(){
        return $this->providers;
    }
    
}
?>
