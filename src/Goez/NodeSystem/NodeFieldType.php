<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;
use ModelValidatingTrait;

class NodeFieldType extends Eloquent
{
    use ModelValidatingTrait;

    public static $tableName = 'goez_node_field_types';
    protected $rules = [
        'node_type_id' => ['required'],
        'field_type_id' => ['required'],
        'display_name' => ['required', 'max:100'],
        'field_name' => ['required', 'max:50']
    ];
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
            $table->string('display_name', 100); // 顯示名稱
            $table->string('field_name', 50); // 欄位名稱
            $table->text('extra_settings')->nullable(); // 額外設定
            $table->integer('sort_order')->default(10); // 排列順序

            $table->index(array('node_type_id', 'field_type_id'), 'goez_node_field_types_pivot');
            $table->index('sort_order');
        };
    }
}
