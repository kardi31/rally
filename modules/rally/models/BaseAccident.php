<?php

/**
 * Rally_Model_Doctrine_BaseAccident
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property decimal $damage
 * @property Rally_Model_Doctrine_StageResult $StageResult
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Rally_Model_Doctrine_BaseAccident extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('rally_accident');
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
        $this->hasColumn('damage', 'decimal', 18, array(
             'type' => 'decimal',
             'length' => '18',
             ));

        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Rally_Model_Doctrine_StageResult as StageResult', array(
             'local' => 'id',
             'foreign' => 'accident_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}