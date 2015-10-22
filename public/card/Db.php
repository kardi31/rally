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
    
    
    public function fetch($sql){
        $data = $this->pdo->prepare($sql);
        $data->execute();
        return $data->fetch();
    }
    
    public function execute($sql){
        $data = $this->pdo->prepare($sql);
        $data->execute();
    }
}
