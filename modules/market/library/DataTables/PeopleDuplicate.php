<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Market_DataTables_PeopleDuplicate{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('pd');
        $q->leftJoin('pd.Offer o');
        $q->leftJoin('o.Player p');
        $q->leftJoin('p.Team t');
        $q->leftJoin('pd.Bid b');
        $q->leftJoin('b.Team bt');
        $q->addSelect('pd.*,o.*,p.*,t.*,b.*,bt.*');
        $q->orderBy('pd.created_at DESC');
//        $q->addWhere('DATE(t.training_date) = CURDATE()');
        return $q;
    }
}
?>
