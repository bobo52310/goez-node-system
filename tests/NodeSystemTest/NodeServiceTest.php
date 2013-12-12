<?php

namespace Goez\NodeSystemTest;

use Goez\NodeSystem\NodeFieldType;
use Goez\NodeSystem\NodeType;
use Goez\NodeSystem\Node;
use Goez\NodeSystem\FieldType;
use Goez\NodeSystem\Field;
use Goez\NodeSystem\NodeTag;
use Goez\NodeSystem\Tag;
use Illuminate\Database\Capsule\Manager as DB;

class NodeServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Illuminate\Database\Capsule\Manager
     */
    protected static $_db = null;

    protected static function _connectTestDb()
    {
        static::$_db = new DB();
        static::$_db->addConnection(array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ));
        static::$_db->setAsGlobal();
        static::$_db->bootEloquent();
    }

    protected static function initTables()
    {
        $conn = static::$_db->getConnection();
        $builder = $conn->getSchemaBuilder();

        $builder->dropIfExists(NodeFieldType::$tableName);
        $builder->dropIfExists(NodeType::$tableName);
        $builder->dropIfExists(Node::$tableName);
        $builder->dropIfExists(FieldType::$tableName);
        $builder->dropIfExists(Field::$tableName);
        $builder->dropIfExists(NodeTag::$tableName);
        $builder->dropIfExists(Tag::$tableName);

        $builder->create(NodeFieldType::$tableName, NodeFieldType::getBlueprint());
        $builder->create(NodeType::$tableName, NodeType::getBlueprint());
        $builder->create(Node::$tableName, Node::getBlueprint());
        $builder->create(FieldType::$tableName, FieldType::getBlueprint());
        $builder->create(Field::$tableName, Field::getBlueprint());
        $builder->create(NodeTag::$tableName, NodeTag::getBlueprint());
        $builder->create(Tag::$tableName, Tag::getBlueprint());
    }

    protected static function _truncateStorage()
    {
        DB::table(NodeFieldType::$tableName)->truncate();
    }

    public static function setUpBeforeClass()
    {
        static::_connectTestDb();
        static::initTables();
    }

    public function setUp()
    {
        static::_truncateStorage();
    }

    public function testCreateNodeType()
    {
        $data = array(
            'name' => 'Test',
            'type' => 'test',
            'description' => '',
            'has_title' => 'y',
        );

//        $nodeType = new NodeType($data);
//        $id = $nodeType->save();


    }
}
