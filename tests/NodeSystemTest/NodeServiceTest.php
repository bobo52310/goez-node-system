<?php

namespace Goez\NodeSystemTest;

use Goez\NodeSystem\NodeFieldType;
use Goez\NodeSystem\NodeType;
use Goez\NodeSystem\Node;
use Goez\NodeSystem\FieldType;
use Goez\NodeSystem\Field;
use Goez\NodeSystem\NodeTag;
use Goez\NodeSystem\Tag;
use Goez\NodeSystem\NodeService;
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
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'kkbox_inc_testing',
            'username'  => 'root',
            'password'  => '123456',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
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
            'field_type' => array(
                1, 2, 3, 4, 1,
            ),
            'field_name' => array(
                'My Text',
                'My Image',
                'My Link',
                'My HTML',
                'My Text 2',
            ),
        );

        $this->assertInstanceOf('Goez\NodeSystem\NodeType', NodeService::saveNodeType($data));

        $nodeType = NodeService::findNodeTypeByName('Test');

        $query = NodeFieldType::query();
        $result = $query->where('node_type_id', $nodeType->id)->lists('name', 'id');

        $this->assertEquals('My Text', $result[1]);
        $this->assertEquals('My Image', $result[2]);
        $this->assertEquals('My Link', $result[3]);
        $this->assertEquals('My HTML', $result[4]);
        $this->assertEquals('My Text 2', $result[5]);
    }
}
