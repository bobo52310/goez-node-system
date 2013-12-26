<?php

namespace Goez\NodeSystemTest;

use Goez\NodeSystem\NodeFieldType;
use Goez\NodeSystem\NodeService;

class NodeServiceTest extends TestCase
{

    public function testCreateNodeType()
    {
        $nodeTypeData = array(
            'name' => 'Test',
            'type' => 'test',
            'description' => '',
            'has_title' => 'y',
            'field_type' => array(
                1, 2, 3, 4, 1,
            ),
            'field_name' => array(
                'my_text',
                'my_image',
                'my_link',
                'my_html',
                'my_text2',
            ),
            'display_name' => array(
                'My Text',
                'My Image',
                'My Link',
                'My HTML',
                'My Text 2',
            ),
            'extra_settings' => array(
                '', '140x140', '', '', '',
            ),
            'sort_order' => array(
                1, 2, 3, 4, 5,
            ),
        );

        $this->assertInstanceOf('Goez\NodeSystem\NodeType', NodeService::saveNodeType($nodeTypeData));

        $nodeType = NodeService::findNodeType('test');

        $query = NodeFieldType::query();
        $result = $query->where('node_type_id', $nodeType->id)->lists('display_name', 'id');

        $this->assertEquals('My Text', $result[1]);
        $this->assertEquals('My Image', $result[2]);
        $this->assertEquals('My Link', $result[3]);
        $this->assertEquals('My HTML', $result[4]);
        $this->assertEquals('My Text 2', $result[5]);
    }
}
