<?php
 
class TK_Paginator{
         
    public function __construct(){
       
    }
    
    public static function paginate($q,$limit,$page = 1) {
        if(!isset($GLOBALS['urlParams']['page'])||(int)$GLOBALS['urlParams']['page']<1){
            $page = 1;
        }
        else{
            $page = (int)$GLOBALS['urlParams']['page'];
        }
        
        $offset = ($page-1)*$limit;
        if($page>1)
            $offset++;
        
        $q->limit($limit);
        $q->offset($offset);
        
        return $q;
    }
    
    
}
  
?>
