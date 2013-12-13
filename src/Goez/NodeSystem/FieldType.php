<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class FieldType extends Eloquent
{
    public static $tableName = 'goez_field_types';
    protected $table = 'goez_field_types';

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->string('type', 100);
            $table->string('name', 100);
            $table->string('description', 200)->nullable()->default(null);
        };
    }
}