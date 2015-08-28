<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Market_DataTables_CarOffer{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('o');
        $q->leftJoin('o.Car c');
        $q->leftJoin('c.Model m');
        $q->leftJoin('c.Team t');
        $q->leftJoin('o.Bids b');
        $q->leftJoin('b.Team bt');
        if(isset($GLOBALS['urlParams'][1])){
            $q->addWhere('o.finish_date > NOW()');
        }
        $q->addSelect('o.*,c.*,t.*,b.*,bt.*,m.*');
        $q->orderBy('o.id DESC, b.value DESC');
//        $q->addWhere('DATE(t.training_date) = CURDATE()');
        return $q;
    }
}
?>
