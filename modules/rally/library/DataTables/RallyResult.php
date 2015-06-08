<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_RallyResult{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('r');
	$q->leftJoin('r.Crew cr');
	$q->leftJoin('cr.Car c');
	$q->leftJoin('cr.Team t');
	$q->leftJoin('cr.Driver d');
	$q->leftJoin('cr.Pilot p');
	$q->addSelect('total_time');
        $q->addSelect('r.*,c.name,cr.id,cr.risk,rally_time,d.last_name,t.name,d.first_name,p.last_name,p.first_name');
	$q->addWhere('r.rally_id = ?',$GLOBALS['urlParams']['id']);
	$q->groupBy('cr.id');
        return $q;
    }
}
?>
