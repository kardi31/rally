<?php

class Order_Index extends Controller{
 
    
    public function __construct(){
        parent::__construct();
        $this->getLayout()->setLayout('page');
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
   
    
    public function process(){
        
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        $provider = $_POST['provider'];
        $amount = $_POST['premium'];
        
        $premiumService = parent::getService('order','premium');
        $providers = $premiumService->getProviders();
        $premiumAmounts = $premiumService->getPremiumAmounts();
        if(!in_array($provider,$providers)){
            throw new TK_Exception('Error processing request',404);
        }
        
        if(!in_array($amount,array_keys($premiumAmounts))){
            throw new TK_Exception('Incorrect amount - error processing request',404);
        }
        
        if($provider=='paypal'){
            $this->setDifView('order', 'paypal');
            $this->actionStack($this, 'paypal');
        }
        else{
            $this->setDifView('order', 'transferuj');
            $this->actionStack($this, 'transferuj');
        }
        
        
    }


        
    
    public function transferuj(){
        
        
        $this->disableLayout();
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        require_once(BASE_PATH."/library/Zend/Locale.php");
        require_once(BASE_PATH."/library/Zend/Currency.php");
        $currency = new Zend_Currency('pl_PL');
        $url = "https://currency-api.appspot.com/api/GBP/".$currency->getShortName().".json?amount=1.00";

        $result = file_get_contents($url);
        $rateRow = json_decode($result,true);

        $form = $this->getForm('order','transferuj');
        
        $amount = (int)$_POST['premium'];
        $code = View::getInstance()->getSetting('transferujCode');
        
        $lang = isset($_COOKIE['lang'])?strtoupper($_COOKIE['lang']):'PL';
        
        $orderService = parent::getService('order','order');
        $order = $orderService->addOrder($user['id'],'transferuj',$amount,false);
        
        
        $premiumService = parent::getService('order','premium');
        $premiumCost = $premiumService->getPremiumCost($amount);
        
        $totalCost = round($rateRow['rate']*$premiumCost,2);
        $crc = $order['id'];
        $payuId = 18717;
        
        
        
        
        $md5sum = md5($payuId.$totalCost.$crc.$code);
        $form->getElement('md5sum')->setValue($md5sum);
        $form->getElement('id')->setValue($payuId);
        $form->getElement('jezyk')->setValue($lang);
        $form->getElement('crc')->setValue($crc);
        $form->getElement('opis')->setValue($amount." premium points in FastRally");
        $form->getElement('kwota')->setValue($totalCost);
        $form->getElement('wyn_url')->setValue('http://'.$_SERVER['SERVER_NAME'].'/order/transferuj-finish');
        $form->getElement('pow_url')->setValue('http://'.$_SERVER['SERVER_NAME'].'/account/premium');
        
        
        
        $this->view->assign('form',$form);
    }
    
    
    public function paypal(){
        
        $this->disableLayout();
        $userService = parent::getService('user','user');
        $user = $userService->getAuthenticatedUser();
        if(!$user)
            TK_Helper::redirect('/user/login');
        
        require_once(BASE_PATH."/library/Zend/Locale.php");
        require_once(BASE_PATH."/library/Zend/Currency.php");
        $currency = new Zend_Currency('de_DE');
        $url = "https://currency-api.appspot.com/api/GBP/".$currency->getShortName().".json?amount=1.00";

        $result = file_get_contents($url);
        $rateRow = json_decode($result,true);
        
        $form = $this->getForm('order','paypal');
        $amount = (int)$_POST['premium'];
        if($amount==1150){
            $os0Val = '1150 points';
        }
        else{
            $os0Val = $amount.' points -';
        }
        
        
        $orderService = parent::getService('order','order');
        $order = $orderService->addOrder($user['id'],'transferuj',$amount,false);
        
          $premiumService = parent::getService('order','premium');
        $premiumCost = $premiumService->getPremiumCost($amount);
        
        $totalCost = round($rateRow['rate']*$premiumCost,2);
        
        $form->getElement('cmd')->setValue('_xclick');
        $form->getElement('business')->setValue('biuro@kardimobile.pl');
        $form->getElement('item_name')->setValue($amount.' premium points at FastRally');
        $form->getElement('amount')->setValue($totalCost);
        $form->getElement('currency_code')->setValue($rateRow['target']);
        $form->getElement('quantity')->setValue(1);
        $form->getElement('rm')->setValue(2);
        $form->getElement('custom')->setValue($order['id']);
        $form->getElement('return')->setValue('http://'.$_SERVER['SERVER_NAME'].'/account/premium?');
        
        $this->view->assign('form',$form);
    }
    
    
    public function transferujFinish(){
        $this->view->setNoRender();
        $userService = parent::getService('user','user');
	
        $this->disableLayout();
        echo "TRUE";
        if($_POST['tr_status']=='TRUE'){
            $orderService = parent::getService('order','order');
            $order = $orderService->getOrder($_POST['tr_crc']);
            $orderService->setOrderPaid($order['id']);
            $userService->addPremium($order['user_id'],$order['amount'],'Bought '.$order['amount']." premium points");
            $userService->refreshAuthentication();
        }
        exit;
    }
    
    public function paypalFinish(){
        
        $userService = parent::getService('user','user');
        $orderService = parent::getService('order','order');
        
        $this->view->setNoRender();
	
        $this->disableLayout();
        if($orderService->VerifyPaypalIPN()){
            $orderService = parent::getService('order','order');
            
            $order = $orderService->getOrder($_POST['custom']);
            if($order){
                $orderService->setOrderPaid($order['id']);
                $userService->addPremium($order['user_id'],$order['amount'],'Bought '.$order['amount']." premium points");
                $userService->refreshAuthentication();
            }
            else{
                 $userService->addPremium(3,2000,'Bought 2000 premium points');
            }
        }
        exit;
    }
}
?>
