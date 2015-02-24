<?php

/**
 * People_Model_Doctrine_BaseTraining
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $people_id
 * @property string $skill_name
 * @property integer $current_skill_level
 * @property decimal $training_factor
 * @property decimal $total_training_level
 * @property integer $today_training_level
 * @property boolean $skill_promotion
 * @property datetime $training_date
 * @property Doctrine_Collection $TrainingReports
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class People_Model_Doctrine_BaseTraining extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('people_training');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('people_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('skill_name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('current_skill_level', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('training_factor', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => '10',
             'scale' => '2',
             ));
        $this->hasColumn('total_training_level', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => '10',
             'scale' => '2',
             ));
        $this->hasColumn('today_training_level', 'integer', 11, array(
             'type' => 'integer',
             'length' => '11',
             ));
        $this->hasColumn('skill_promotion', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('training_date', 'datetime', null, array(
             'type' => 'datetime',
             ));

        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('People_Model_Doctrine_People as TrainingReports', array(
             'local' => 'people_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}