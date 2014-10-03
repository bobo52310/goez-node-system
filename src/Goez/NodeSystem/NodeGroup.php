<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class NodeGroup extends Eloquent
{
    public static $tableName = 'goez_node_groups';
    public $timestamps = false;
    protected $table = 'goez_node_groups';
    protected $guarded = array();
    const NODE_ID = 'node_id';
    const GROUP_ID = 'group_id';

    public function getNodeIdColumn()
    {
        return static::NODE_ID;
    }

    public function getGroupIdColumn()
    {
        return static::GROUP_ID;
    }

    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('node_id');
            $table->integer('group_id');

            $table->index('node_id');
            $table->index('group_id');
        };
    }
}
