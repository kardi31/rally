<?php

/**
 * Rally_Model_Doctrine_BaseStage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $rally_id
 * @property string $name
 * @property decimal $length
 * @property boolean $finished
 * @property timestamp $date
 * @property time $min_time
 * @property Rally_Model_Doctrine_Rally $Rally
 * @property Doctrine_Collection $Results
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Rally_Model_Doctrine_BaseStage extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('rally_stage');
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
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('length', 'decimal', 18, array(
             'type' => 'decimal',
             'length' => '18',
             ));
        $this->hasColumn('finished', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('min_time', 'time', null, array(
             'type' => 'time',
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

        $this->hasMany('Rally_Model_Doctrine_StageResult as Results', array(
             'local' => 'id',
             'foreign' => 'stage_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}