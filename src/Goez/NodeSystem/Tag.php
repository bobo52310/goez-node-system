<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class Tag extends Eloquent
{
    public static $tableName = 'goez_tags';
    protected $table = 'goez_tags';

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->string('name', 100); // 標籤名稱
        };
    }
}
