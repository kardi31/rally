<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Forum_DataTables_Thread{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('t');
        $q->leftJoin('t.Posts p');
        $q->leftJoin('t.User u');
        $q->leftJoin('t.Category c');
        $q->addSelect('p.*,t.*,u.*,c.*');
        return $q;
    }
}
?>
