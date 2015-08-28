<?php

if(count($_GET)>0){
        $keys = array_keys($_GET);
        $params = explode('/',$keys[0]);
        if(count($params)>1){
            $module = $params[0];
            $action = $params[1];
            unset($params[0]);
            unset($params[1]);
        }
        else{
            $module = "index";
            $action = $params[0];
        }
        $params = array_values($params);
        $urlParams = array();
        for($i=0;$i<count($params);$i++):
            $urlParams[$i+1] = filter_var($params[$i], FILTER_SANITIZE_URL);
        endfor;
        $GLOBALS['urlParams'] = $urlParams; 
}

if(!empty($_POST)){
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value);
      }
}

        if(!isset($module))
            $module = "index";

        if(!isset($action))
            $action = "index";
        
      
        
        
?>
