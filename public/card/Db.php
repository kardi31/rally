<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db
 *
 * @author Tomasz
 */
class Db {
    
    protected $pdo;
    
    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=ral;charset=utf8', 'root', '');
    }
    
    
    public function fetch($sql,$fetch = PDO::FETCH_ASSOC){
        $data = $this->pdo->prepare($sql);
        $data->execute();
        return $data->fetch($fetch);
    }
    
    public function fetchAll($sql,$fetch = PDO::FETCH_ASSOC){
        $data = $this->pdo->prepare($sql);
        $data->execute();
        return $data->fetchAll($fetch);
    }
    
    public function execute($sql){
        $data = $this->pdo->prepare($sql);
        $data->execute();
    }
}
