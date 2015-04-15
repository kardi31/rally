<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Forum_DataTables_SponsorList{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('s');
        $q->addSelect('s.*');
        $q->addSelect('count(st.id) as forums_count');
	$q->leftJoin('s.SponsoredForums st');
        $q->groupBy('s.id');
        return $q;
    }
}
?>
