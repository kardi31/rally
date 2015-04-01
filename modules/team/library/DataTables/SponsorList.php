<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Team_DataTables_SponsorList{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('s');
        $q->addSelect('s.*');
        $q->addSelect('count(s.id) as teams_count');
	$q->leftJoin('s.SponsoredTeams st');
        $q->groupBy('s.id');
        return $q;
    }
}
?>
