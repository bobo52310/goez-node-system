<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint as Table;

class CreateNodeSystemTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // fields: 實際存放 field 資料的表格，例如一篇文章的段落內容、圖片資訊、上傳檔案資訊等。
        // nodes 與 fields 為一對多關係。
        Schema::create('fields', function (Table $table) {
            $table->increments('id');
            $table->integer('node_id');
            $table->integer('type_id');
            $table->text('body_value');
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });

        // field_types: 存放目前有哪些 field 類型的表格，例如：段落、圖片、上傳檔案、評論、廣告連結等。
        Schema::create('field_types', function (Table $table) {
            $table->increments('id');
            $table->string('type', 100);
            $table->string('name', 100);
            $table->string('description', 200)->nullable()->default(null);
        });

        // nodes: 實際存放 node 資料的表格，例如每篇文章的標題、描述、發佈時間等。
        Schema::create('nodes', function (Table $table) {
            $table->increments('id');
            $table->integer('user_id', 10);
            $table->string('type', 100);
            $table->string('title', 200)->nullable()->default(null);
            $table->string('summary', 200)->nullable()->default(null);
            $table->datetime('published_at')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });

        // node_types: 存放目前有哪些 node 類型的表格，例如：關於 KKBOX 、風雲榜、廣告。
        Schema::create('node_types', function (Table $table) {
            $table->increments('id');
            $table->string('type', 100); // Node 類型的英文名稱
            $table->string('name', 100); // Node 類型的中文名稱
            $table->string('description', 200)->nullable()->default(null); // 類型描述
            $table->enum('has_title', array('y', 'n'))->default('y'); // 是否要在 form 上出現標題欄位
        });

        // node_tags: 標註 nodes 與 tags 的關係。
        Schema::create('node_types', function (Table $table) {
            $table->increments('id');
            $table->integer('node_id'); // 對應到 nodes.id
            $table->integer('tag_id'); // 對應到 tags.id
        });

        // tags: 用以對 node 做分類的表格。
        Schema::create('tags', function (Table $table) {
            $table->increments('id');
            $table->string('name', 100); // 標籤名稱
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fields');
        Schema::drop('field_types');
        Schema::drop('nodes');
        Schema::drop('node_types');
        Schema::drop('node_tags');
        Schema::drop('tags');
    }

}

/*



