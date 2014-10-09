<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Goez\NodeSystem\NodeGroup;

class CreateNodeGroupTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(NodeGroup::$tableName)) {
            Schema::create(NodeGroup::$tableName, NodeGroup::getBlueprint());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable(NodeGroup::$tableName)) {
            Schema::drop(NodeGroup::$tableName);
        }
    }

}
