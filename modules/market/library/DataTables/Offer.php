<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Market_DataTables_Offer{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('o');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('b.Team bt');
        if(isset($GLOBALS['urlParams']['future'])){
            $q->addWhere('o.finish_date > NOW()');
        }
        $q->addSelect('o.*,p.*,t.*,b.*,bt.*');
        $q->orderBy('o.id DESC, b.value DESC');
//        $q->addWhere('DATE(t.training_date) = CURDATE()');
        return $q;
    }
}
?>
