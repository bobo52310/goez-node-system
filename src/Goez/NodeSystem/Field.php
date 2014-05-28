<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class Field extends Eloquent
{
    public static $tableName = 'goez_fields';
    protected $table = 'goez_fields';
    protected $guarded = array();
    public $timestamps = false;

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
            $table->integer('created_at')->unsigned()->nullable();
            $table->integer('updated_at')->unsigned()->nullable();
            $table->softDeletes();

            $table->index('node_id');
            $table->index('field_name');
            $table->index('sort_order');
            $table->index('created_at');
        };
    }

    /**
     * Avoid to auto convert to date-time format.
     * @return int
     */
    public function freshTimestamp()
    {
        return time();
    }

    /**
     * @return array
     */
    public function getDates()
    {
        return array();
    }
}

