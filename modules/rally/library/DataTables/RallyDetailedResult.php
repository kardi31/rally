<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_RallyDetailedResult{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('r');
	$q->leftJoin('r.Crew cr');
	$q->leftJoin('cr.Car c');
	$q->leftJoin('c.Model m');
	$q->leftJoin('cr.Team t');
	$q->leftJoin('cr.Driver d');
	$q->leftJoin('cr.Pilot p');
	$q->addSelect('total_time');
        $q->addSelect('r.*,cr.id,cr.risk,cr.team_id,,rally_time,d.last_name,t.name,d.*,p.*,c.*,m.*');
	$q->addWhere('r.rally_id = ?',$GLOBALS['urlParams'][1]);
	$q->groupBy('cr.id');
        return $q;
    }
}
?>
