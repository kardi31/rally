<?php

class Test_Index extends Controller{
 
    public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this, $viewName);
    }
    
    public function redirect($param){
        return null;
    }
    
    public function index(){
        require_once(BASE_PATH."/modules/user/controllers/TestController.php");
        $trainingService = parent::getService('people','training');
        $userContr = new User_Test();
        $randomNumber = rand(10000,12000);
        $_POST['password'] = "portal";
        $_POST['email'] = "test_".$randomNumber."@kardimobile.pl";
        $user = $userContr->register();
        $GLOBALS['urlParams']['token'] = $user['token'];
        $userContr->activate();
        echo "good";exit;
    }
    
    
    
}
?>
