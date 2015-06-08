<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_Crew{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('rc');
        $q->addSelect('rc.*');
	$q->leftJoin('rc.Driver d');
	$q->leftJoin('rc.Pilot p');
	$q->leftJoin('rc.Car c');
	$q->leftJoin('c.Model cm');
	$q->leftJoin('rc.Team t');
	$q->leftJoin('rc.Rally r');
	$q->addSelect('p.last_name,p.first_name');
	$q->addSelect('d.last_name,d.first_name');
	$q->addSelect('c.id');
	$q->addSelect('t.name');
	$q->addSelect('cm.name');
	$q->addSelect('r.id');
        $q->addWhere('r.id = ?',$GLOBALS['urlParams']['id']);
        return $q;
    }
}
?>
