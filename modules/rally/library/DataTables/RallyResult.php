<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_RallyResult{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('sr');
	$q->leftJoin('sr.Crew cr');
	$q->leftJoin('sr.Stage s');
	$q->leftJoin('sr.Accident a');
	$q->leftJoin('cr.Car c');
	$q->leftJoin('cr.Team t');
	$q->leftJoin('cr.Driver d');
	$q->leftJoin('cr.Pilot p');
	$q->addSelect('SEC_TO_TIME(SUM(TIME_TO_SEC(sr.base_time))) as rally_time');
        $q->addSelect('sr.*,c.name,cr.id,cr.risk,rally_time,d.last_name,t.name,d.first_name,p.last_name,p.first_name');
	$q->addWhere('s.rally_id = ?',$GLOBALS['urlParams']['rally-id']);
	$q->groupBy('cr.id');
        return $q;
    }
}
?>
