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
        $this->createFieldsTable();
        $this->createFieldTypesTable();
        $this->createNodesTable();
        $this->createNodeTypesTable();
        $this->createNodeFieldsTable();
        $this->createNodeTagsTable();
        $this->createTagsTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('goez_fields');
        Schema::drop('goez_field_types');
        Schema::drop('goez_nodes');
        Schema::drop('goez_node_types');
        Schema::drop('goez_node_field_types');
        Schema::drop('goez_node_tags');
        Schema::drop('goez_tags');
    }

    /**
     * field_types: 存放目前有哪些 field 類型的表格，例如：段落、圖片、上傳檔案、評論、廣告連結等
     */
    protected function createFieldTypesTable()
    {
        Schema::create('goez_field_types', function (Table $table) {
            $table->increments('id');
            $table->string('type', 100);
            $table->string('name', 100);
            $table->string('description', 200)->nullable()->default(null);
        });

        $types = array(
            array(
                'id' => 1,
                'type' => 'text',
                'name' => 'Text',
                'description' => null,
            ),
            array(
                'id' => 2,
                'type' => 'image',
                'name' => 'Image',
                'description' => null,
            ),
            array(
                'id' => 3,
                'type' => 'link',
                'name' => 'Link',
                'description' => null,
            ),
            array(
                'id' => 4,
                'type' => 'html',
                'name' => 'HTML',
                'description' => null,
            ),
        );

        DB::table('goez_field_types')->insert($types);
    }

    /**
     * fields: 實際存放 field 資料的表格，例如一篇文章的段落內容、圖片資訊、上傳檔案資訊等
     * nodes 與 fields 為一對多關係
     */
    protected function createFieldsTable()
    {
        Schema::create('goez_fields', function (Table $table) {
            $table->increments('id');
            $table->integer('node_id');
            $table->integer('type_id');
            $table->text('body_value');
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * nodes: 實際存放 node 資料的表格，例如每篇文章的標題、描述、發佈時間等
     */
    protected function createNodesTable()
    {
        Schema::create('goez_nodes', function (Table $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('type', 100);
            $table->string('title', 200)->nullable()->default(null);
            $table->string('summary', 200)->nullable()->default(null);
            $table->datetime('published_at')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->datetime('created_at');
            $table->datetime('updated_at')->nullable();
        });
    }

    /**
     * node_types: 存放目前有哪些 node 類型的表格，例如：關於 KKBOX 、風雲榜、廣告
     */
    protected function createNodeTypesTable()
    {
        Schema::create('goez_node_types', function (Table $table) {
            $table->increments('id');
            $table->string('type', 100); // Node 類型的英文名稱
            $table->string('name', 100); // Node 類型的中文名稱
            $table->string('description', 200)->nullable()->default(null); // 類型描述
            $table->enum('has_title', array('y', 'n'))->default('y'); // 是否要在 form 上出現標題欄位
        });
    }

    /**
     * node_fields: 標註 nodes 與 field_types 的關係
     */
    protected function createNodeFieldsTable()
    {
        Schema::create('goez_node_field_types', function (Table $table) {
            $table->increments('id');
            $table->integer('node_id'); // 對應到 nodes.id
            $table->integer('type_id'); // 對應到 field_types.id
        });
    }

    /**
     * node_tags: 標註 nodes 與 tags 的關係
     */
    protected function createNodeTagsTable()
    {
        Schema::create('goez_node_tags', function (Table $table) {
            $table->increments('id');
            $table->integer('node_id'); // 對應到 nodes.id
            $table->integer('tag_id'); // 對應到 tags.id
        });
    }

    /**
     * tags: 用以對 node 做分類的表格
     */
    protected function createTagsTable()
    {
        Schema::create('goez_tags', function (Table $table) {
            $table->increments('id');
            $table->string('name', 100); // 標籤名稱
        });
    }

}
