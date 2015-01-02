<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class User_DataTables_User{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('u');
        $q->addSelect('u.*');
        return $q;
    }
}
?>
