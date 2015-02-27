<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class People_DataTables_Training{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('t');
        $q->leftJoin('t.People p');
        $q->leftJoin('p.Team te');
        $q->addSelect('t.*,p.*,te.*');
//        $q->orderBy('km_passed_today/max_available_km_passed_today');
        $q->addWhere('DATE(t.training_date) = CURDATE()');
        return $q;
    }
}
?>
