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
    
    function VerifyPaypalIPN(array $IPN = null){
    if(empty($IPN)){
        $IPN = $_POST;
    }
    if(empty($IPN['verify_sign'])){
        return null;
    }
    $IPN['cmd'] = '_notify-validate';
//    $PaypalHost = (empty($IPN['test_ipn']) ? 'www' : 'www.sandbox').'.paypal.com';
    $PaypalHost = 'www.sandbox.paypal.com';
    $cURL = curl_init();
    curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($cURL, CURLOPT_URL, "https://{$PaypalHost}/cgi-bin/webscr");
    curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
    curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($cURL, CURLOPT_POST, true); // POST back
    curl_setopt($cURL, CURLOPT_POSTFIELDS, $IPN); // the $IPN
    curl_setopt($cURL, CURLOPT_HEADER, false);
    curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
    curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
    curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
    curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
        'Connection: close',
        'Expect: ',
    ));
    $Response = curl_exec($cURL);
    $Status = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);
    curl_close($cURL);
    if(empty($Response) or !preg_match('~^(VERIFIED|INVALID)$~i', $Response = trim($Response)) or !$Status){
        return null;
    }
    if(intval($Status / 100) != 2){
        return false;
    }
    return !strcasecmp($Response, 'VERIFIED');
    }
}
?>
