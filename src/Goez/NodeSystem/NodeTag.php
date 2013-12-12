<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class NodeTag extends Eloquent
{
    public static $tableName = 'goez_node_tags';
    protected $table = 'goez_node_tags';

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('node_id'); // 對應到 nodes.id
            $table->integer('tag_id'); // 對應到 tags.id
        };
    }
}
