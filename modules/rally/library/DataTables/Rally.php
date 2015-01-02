<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Rally_DataTables_Rally{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('r');
        $q->addSelect('r.*');
        return $q;
    }
}
?>
