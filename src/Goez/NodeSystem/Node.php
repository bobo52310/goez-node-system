<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class Node extends Eloquent
{
    public static $tableName = 'goez_nodes';
    protected $table = 'goez_nodes';
    protected $guarded = array();
    public static $rules = array(
        'title' => 'max:100|required',
        'user_id' => 'integer|required',
    );

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

    /**
     * @return array
     */
    public function fieldsList()
    {
        return Field::query()->where('node_id', $this->id)
                             ->orderBy('id')
                             ->lists('body_value', 'field_name');
    }

    /**
     * @return array
     */
    public function tagIds()
    {
        return NodeTag::query()->where('node_id', $this->id)
                               ->orderBy('tag_id')
                               ->lists('tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(
            'Goez\NodeSystem\Tag',
            'goez_node_tags',
            'node_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(
            'Goez\NodeSystem\Language',
            'goez_node_languages',
            'node_id');
    }

}
