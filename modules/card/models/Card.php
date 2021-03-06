<?php

/**
 * Card_Model_Doctrine_Card
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Card_Model_Doctrine_Card extends Card_Model_Doctrine_BaseCard
{
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Car_Model_Doctrine_CarModels as Model', array(
             'local' => 'car_model_id',
             'foreign' => 'id'));
    }
}