<?php

/**
 * Team_Model_Doctrine_TeamTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Team_Model_Doctrine_TeamTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Team_Model_Doctrine_TeamTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Team_Model_Doctrine_Team');
    }
}