<?php

/**
 * Rally_Model_Doctrine_BaseCrew
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $rally_id
 * @property integer $team_id
 * @property integer $driver_id
 * @property integer $pilot_id
 * @property integer $car_id
 * @property string $risk
 * @property boolean $in_race
 * @property Rally_Model_Doctrine_Rally $Rally
 * @property Doctrine_Collection $StageResults
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Rally_Model_Doctrine_BaseCrew extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('rally_crew');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('rally_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('team_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('driver_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('pilot_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('car_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('risk', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('in_race', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('in_race', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('km_passed', 'float', 4, array(
             'type' => 'float',
             'length' => '4',
             'scale' => '2',
             ));
        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Rally_Model_Doctrine_Rally as Rally', array(
             'local' => 'rally_id',
             'foreign' => 'id'));

        $this->hasMany('Rally_Model_Doctrine_StageResult as StageResults', array(
             'local' => 'id',
             'foreign' => 'crew_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}