<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_StageResult{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('sr');
	$q->leftJoin('sr.Crew cr');
	$q->leftJoin('sr.Accident a');
	$q->leftJoin('cr.Car c');
	$q->leftJoin('cr.Team t');
	$q->leftJoin('cr.Driver d');
	$q->leftJoin('cr.Pilot p');
        $q->addSelect('sr.*,a.*,c.name,cr.id,cr.risk,d.last_name,t.name,d.first_name,p.last_name,p.first_name');
	$q->addWhere('sr.stage_id = ?',$GLOBALS['urlParams']['stage-id']);
        return $q;
    }
}
?>
