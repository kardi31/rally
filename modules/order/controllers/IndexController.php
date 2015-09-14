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
        
        $totalCost = $rateRow['rate']*($amount/100);
        $crc = 'tomCrC56';
        $payuId = 18717;
        $md5sum = md5($payuId.$totalCost.$crc.$code);
        $form->getElement('md5sum')->setValue($md5sum);
        $form->getElement('id')->setValue($payuId);
        $form->getElement('jezyk')->setValue($lang);
        $form->getElement('crc')->setValue($crc);
        $form->getElement('opis')->setValue($amount." premium points in FastRally");
        $form->getElement('kwota')->setValue($totalCost);
        
        $this->view->assign('form',$form);
    }
    
    
    public function paypal(){
        require_once(BASE_PATH."/library/Zend/Locale.php");
        require_once(BASE_PATH."/library/Zend/Currency.php");
        $currency = new Zend_Currency('de_DE');
        $url = "https://currency-api.appspot.com/api/GBP/".$currency->getShortName().".json?amount=1.00";

        $result = file_get_contents($url);
        $rateRow = json_decode($result,true);
//        var_dump($rateRow);exit;
        $form = $this->getForm('order','paypal');
        $amount = (int)$_POST['premium'];
        if($amount==1150){
            $os0Val = '1150 points';
        }
        else{
            $os0Val = $amount.' points -';
        }
        
        
        $paypalBtnId = View::getInstance()->getSetting('paypalBtnId');
        $form->getElement('cmd')->setValue('_s-xclick');
        $form->getElement('hosted_button_id')->setValue($paypalBtnId);
        $form->getElement('on0')->setValue('Amount');
        $form->getElement('os0')->setValue($os0Val);
        $form->getElement('currency_code')->setValue($rateRow['target']);
        $this->view->assign('form',$form);
//        $this->getLayout()->setLayout('page');
        
    }
    
}
?>
