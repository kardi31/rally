<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Market_DataTables_OfferIllegal{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('o');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('b.Team bt');
        if(isset($GLOBALS['urlParams'][1])){
            $q->addWhere('o.finish_date > NOW()');
        }
        $q->addSelect('o.*,p.*,t.*,b.*,bt.*');
        $q->orderBy('o.id DESC, b.value DESC');
        $q->addWhere('o.user_ip = b.user_ip');
        return $q;
    }
}
?>
