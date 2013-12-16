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
            $table->text('field_name', 50);
            $table->text('body_value');
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        };
    }
}

