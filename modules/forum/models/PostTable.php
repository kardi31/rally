<?php

/**
 * Forum_Model_Doctrine_PostTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Forum_Model_Doctrine_PostTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Forum_Model_Doctrine_PostTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Forum_Model_Doctrine_Post');
    }
}