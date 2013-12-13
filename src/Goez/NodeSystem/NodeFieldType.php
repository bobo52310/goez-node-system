<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class NodeFieldType extends Eloquent
{
    public static $tableName = 'goez_node_field_types';
    public $timestamps = false;
    protected $table = 'goez_node_field_types';
    protected $guarded = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('node_type_id'); // 對應到 nodes_types.id
            $table->integer('field_type_id'); // 對應到 field_types.id
            $table->string('name', 100); // 欄位名稱
        };
    }
}
