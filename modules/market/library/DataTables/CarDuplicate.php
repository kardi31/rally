<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Market_DataTables_CarDuplicate{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('pd');
        $q->leftJoin('pd.Offer o');
        $q->leftJoin('o.Car c');
        $q->leftJoin('c.Model m');
        $q->leftJoin('c.Team t');
        $q->leftJoin('pd.Bid b');
        $q->leftJoin('b.Team bt');
        $q->addSelect('pd.*,o.*,c.*,t.*,b.*,bt.*,m.*');
        $q->orderBy('pd.created_at DESC');
//        $q->addWhere('DATE(t.training_date) = CURDATE()');
        return $q;
    }
}
?>
