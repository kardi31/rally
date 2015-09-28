<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Card_DataTables_Post{
    public function getBaseQuery($table){
        
        $q = $table->createQuery('p');
        $q->leftJoin('p.Thread t');
        $q->leftJoin('p.User u');
        $q->leftJoin('t.Category c');
        $q->addSelect('p.*,t.*,u.*,c.*');
        return $q;
    }
}
?>
