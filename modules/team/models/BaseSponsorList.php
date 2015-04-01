<?php

/**
 * Team_Model_Doctrine_BaseSponsorList
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property text $description
 * @property boolean $active
 * @property string $logo
 * @property Doctrine_Collection $SponsoredTeams
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Team_Model_Doctrine_BaseSponsorList extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('team_sponsor_list');
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
        $this->hasColumn('slug', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('description', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('logo', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));

        $this->option('type', 'MyISAM');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Team_Model_Doctrine_Team as SponsoredTeams', array(
             'local' => 'id',
             'foreign' => 'sponsor_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}