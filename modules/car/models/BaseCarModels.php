<?php

/**
 * Car_Model_Doctrine_BaseCarModels
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $capacity
 * @property integer $horsepower
 * @property integer $max_speed
 * @property float $acceleration
 * @property enum $wheel_drive
 * @property integer $league
 * @property integer $price
 * @property integer $real_value
 * @property boolean $on_market
 * @property string $photo
 * @property boolean $unique
 * @property Car_Model_Doctrine_Car $Car
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Car_Model_Doctrine_BaseCarModels extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('car_car_models');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('capacity', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('horsepower', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('max_speed', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('acceleration', 'float', 5, array(
             'type' => 'float',
             'length' => '5',
             'scale' => '2',
             ));
        $this->hasColumn('wheel_drive', 'enum', 11, array(
             'type' => 'enum',
             'length' => 11,
             'values' => 
             array(
              0 => 'front',
              1 => 'rear',
              2 => '4x4',
             ),
             ));
        $this->hasColumn('league', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('price', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('real_value', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('on_market', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('photo', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('unique', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));

        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Car_Model_Doctrine_Car as Car', array(
             'local' => 'id',
             'foreign' => 'model_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}