<?php

/**
 * Market_Model_Doctrine_CarOffer
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Market_Model_Doctrine_CarOffer extends Market_Model_Doctrine_BaseCarOffer
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Car_Model_Doctrine_Car as Car', array(
             'local' => 'car_id',
             'foreign' => 'id'));

        $this->hasOne('Team_Model_Doctrine_Team as Team', array(
             'local' => 'team_id',
             'foreign' => 'id'));
    }
}