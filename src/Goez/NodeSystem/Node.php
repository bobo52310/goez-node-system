<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class Node extends Eloquent
{
    public static $tableName = 'goez_nodes';
    protected $table = 'goez_nodes';

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('type', 100);
            $table->string('title', 200)->nullable()->default(null);
            $table->string('summary', 200)->nullable()->default(null);
            $table->datetime('published_at')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        };
    }
}
