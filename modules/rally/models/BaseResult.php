<?php

/**
 * Rally_Model_Doctrine_BaseResult
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $rally_id
 * @property integer $crew_id
 * @property time $total_time
 * @property boolean $out_of_race
 * @property integer $stage_out_id
 * @property integer $stage_out_number
 * @property integer $position
 * @property Rally_Model_Doctrine_Rally $Rally
 * @property Rally_Model_Doctrine_Crew $Crew
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Rally_Model_Doctrine_BaseResult extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('rally_result');
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
        $this->hasColumn('crew_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('total_time', 'time', null, array(
             'type' => 'time',
             ));
        $this->hasColumn('out_of_race', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('stage_out_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('stage_out_number', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('position', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
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

        $this->hasOne('Rally_Model_Doctrine_Crew as Crew', array(
             'local' => 'crew_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}