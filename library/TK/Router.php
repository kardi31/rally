<?php
if(count($_GET)>0){
$keys = array_keys($_GET);
        $params = explode('/',$keys[0]);
        $module = $params[0];
        $action = $params[1];
        unset($params[0]);
        unset($params[1]);
        
        
          $params = array_values($params);
        $urlParams = array();
        for($i=0;$i<count($params);$i = $i+2):
            $urlParams[$params[$i]] = $params[$i+1];
        endfor;
        $GLOBALS['urlParams'] = $urlParams; 
}
        if(!isset($module))
            $module = "index";

        if(!isset($action))
            $action = "index";
        
      
        
        
?>
