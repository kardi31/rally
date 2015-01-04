<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_Stage{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('s');
        $q->addSelect('s.*');
	$q->addWhere('s.rally_id = ?',$GLOBALS['urlParams']['id']);
        return $q;
    }
}
?>
