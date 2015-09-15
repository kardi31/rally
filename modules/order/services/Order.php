<?php

class OrderService extends Service{
    
    
    protected $orderTable;
    
     private static $instance = NULL;

    static public function getInstance()
    {
       if (self::$instance === NULL)
          self::$instance = new OrderService();
       return self::$instance;
    }
    
    public function __construct(){
        $this->orderTable = parent::getTable('order','order');
    }
    
    public function getOrder($id,$field = 'id',$hydrationMode = Doctrine_Core::HYDRATE_RECORD){
        return $this->orderTable->findOneBy($field,$id,$hydrationMode);
    }
       
    
    public function addOrder($user_id,$provider,$amount,$paid = false){
        $orderArray = array(
            'user_id' => $user_id,
            'provider' => $provider,
            'amount' => $amount
        );
        if($paid){
            $orderArray['paid'] = 1;
            $orderArray['paid_date'] = date('Y-m-d H:i:s');
        }
        
        $order = $this->orderTable->getRecord();
        $order->fromArray($orderArray);
        $order->save();
        
        return $order;
    }
    
    
    public function setOrderPaid($order_id){
        $order = $this->getOrder($order_id);
        $order->set('paid',1);
        $order->set('paid_date',date('Y-m-d H:i:s'));
        $order->save();
        
        return $order;
    }
    
}
?>
