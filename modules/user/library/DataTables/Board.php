<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class User_DataTables_Board{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('b');
        $q->leftJoin('b.User u');
        $q->leftJoin('b.Writer w');
        $q->addSelect('b.*,w.username,u.username');
        return $q;
    }
}
?>
