<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;
use ModelValidatingTrait;

class NodeLanguage extends Eloquent
{
    use ModelValidatingTrait;

    public static $tableName = 'goez_node_languages';
    protected $rules = [
        'node_id' => ['required'],
        'language_name' => ['required', 'max:10']
    ];
    public $timestamps = false;
    protected $table = 'goez_node_languages';
    protected $guarded = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('node_id'); // 對應到 nodes.id
            $table->string('language_name', 10); // 對應到 languages.name

            $table->index('node_id');
            $table->index('language_name');
        };
    }
}
