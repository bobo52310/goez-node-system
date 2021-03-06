<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint as Table;
use Goez\NodeSystem\FieldType;
use Goez\NodeSystem\Field;
use Goez\NodeSystem\NodeType;
use Goez\NodeSystem\Node;
use Goez\NodeSystem\NodeFieldType;
use Goez\NodeSystem\NodeTag;
use Goez\NodeSystem\Tag;
use Goez\NodeSystem\NodeLanguage;
use Goez\NodeSystem\Language;

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
        $this->createTagsTable();
        $this->createNodeTagsTable();
        $this->createNodeFieldsTable();
        $this->createLanguagesTable();
        $this->createNodeLanguagesTable();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(NodeLanguage::$tableName);
        Schema::drop(Language::$tableName);
        Schema::drop(NodeFieldType::$tableName);
        Schema::drop(NodeTag::$tableName);
        Schema::drop(Tag::$tableName);
        Schema::drop(Node::$tableName);
        Schema::drop(NodeType::$tableName);
        Schema::drop(Field::$tableName);
        Schema::drop(FieldType::$tableName);
    }

    /**
     * field_types: 存放目前有哪些 field 類型的表格，例如：段落、圖片、上傳檔案、評論、廣告連結等
     */
    protected function createFieldTypesTable()
    {
        Schema::create(FieldType::$tableName, FieldType::getBlueprint());
        DB::table(FieldType::$tableName)->insert(FieldType::$types);
    }

    /**
     * fields: 實際存放 field 資料的表格，例如一篇文章的段落內容、圖片資訊、上傳檔案資訊等
     * nodes 與 fields 為一對多關係
     */
    protected function createFieldsTable()
    {
        Schema::create(Field::$tableName, Field::getBlueprint());
    }

    /**
     * nodes: 實際存放 node 資料的表格，例如每篇文章的標題、描述、發佈時間等
     */
    protected function createNodesTable()
    {
        Schema::create(Node::$tableName, Node::getBlueprint());
    }

    /**
     * node_types: 存放目前有哪些 node 類型的表格，例如：關於 KKBOX 、風雲榜、廣告
     */
    protected function createNodeTypesTable()
    {
        Schema::create(NodeType::$tableName, NodeType::getBlueprint());
    }

    /**
     * node_fields: 標註 nodes 與 field_types 的關係
     */
    protected function createNodeFieldsTable()
    {
        Schema::create(NodeFieldType::$tableName, NodeFieldType::getBlueprint());
    }

    /**
     * node_tags: 標註 nodes 與 tags 的關係
     */
    protected function createNodeTagsTable()
    {
        Schema::create(NodeTag::$tableName, NodeTag::getBlueprint());
    }

    /**
     * tags: 用以對 node 做分類的表格
     */
    protected function createTagsTable()
    {
        Schema::create(Tag::$tableName, Tag::getBlueprint());
    }

    /**
     * node_tags: 標註 nodes 與 tags 的關係
     */
    protected function createNodeLanguagesTable()
    {
        Schema::create(NodeLanguage::$tableName, NodeLanguage::getBlueprint());
    }

    /**
     * tags: 用以對 node 做分類的表格
     */
    protected function createLanguagesTable()
    {
        Schema::create(Language::$tableName, Language::getBlueprint());

        $langs = array(
            array(
                'id' => 1,
                'name' => 'zh-tw',
                'display_name' => '繁體中文',
            ),
            array(
                'id' => 2,
                'name' => 'zh-cn',
                'display_name' => '简体中文',
            ),
            array(
                'id' => 3,
                'name' => 'en',
                'display_name' => 'English',
            ),
            array(
                'id' => 4,
                'name' => 'jp',
                'display_name' => '日本語',
            ),
            array(
                'id' => 5,
                'name' => 'th',
                'display_name' => 'ภาษาไทย',
            ),
        );

        DB::table(Language::$tableName)->insert($langs);
    }
}
