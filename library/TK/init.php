<?php

class init{
    public function __construct() {
         require(BASE_PATH.'/library/TK/Controller.php');
         require(BASE_PATH.'/library/TK/View.php');
         require(BASE_PATH.'/library/TK/Service.php');
         require(BASE_PATH.'/library/TK/Helper.php');
         require(BASE_PATH.'/library/TK/Paginator.php');
         require(BASE_PATH.'/library/TK/Form/Form.php');
         require(BASE_PATH.'/library/TK/Text.php');
         require(BASE_PATH.'/library/TK/Layout.php');
         require(BASE_PATH.'/library/Zend/Debug.php');
         
    }
}
?>
