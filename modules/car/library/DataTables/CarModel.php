<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Car_DataTables_CarModel{
    public function getBaseQuery($table){
        
        
        $q = $table->createQuery('cm');
        $q->addSelect('cm.*');
        return $q;
    }
}
?>
