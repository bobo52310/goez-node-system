<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class Field extends Eloquent
{
    public static $tableName = 'goez_fields';
    protected $table = 'goez_fields';
    protected $guarded = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('node_id');
            $table->integer('parent_id')->nullable();
            $table->string('field_name', 50);
            $table->text('body_value');
            $table->integer('sort_order')->default(10);
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();

            $table->index('node_id');
            $table->index('field_name');
            $table->index('sort_order');
            $table->index('created_at');
        };
    }
}

