<?php

/**
 * Forum_Model_Doctrine_BaseThread
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property string $title
 * @property text $content
 * @property boolean $pinned
 * @property boolean $active
 * @property text $moderator_notes
 * @property timestamp $moderator_date
 * @property string $moderator_name
 * @property Forum_Model_Doctrine_Category $Category
 * @property Doctrine_Collection $Posts
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     Tomasz <kardi31@o2.pl>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Forum_Model_Doctrine_BaseThread extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('forum_thread');
        $this->hasColumn('id', 'integer', 4, array(
             'primary' => true,
             'autoincrement' => true,
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('category_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('content', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('pinned', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('moderator_notes', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('moderator_date', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('moderator_name', 'string', 255, array(
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
        $this->hasOne('Forum_Model_Doctrine_Category as Category', array(
             'local' => 'category_id',
             'foreign' => 'id'));

        $this->hasMany('Forum_Model_Doctrine_Post as Posts', array(
             'local' => 'id',
             'foreign' => 'thread_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}