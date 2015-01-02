<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Index_Admin extends Controller{
    
     public function __construct(){
        parent::__construct();
    }
    
    public function render($viewName) {
        parent::_render($this,$viewName,'admin');
    }
    
    public function index(){
        
    }
    
}
?>
