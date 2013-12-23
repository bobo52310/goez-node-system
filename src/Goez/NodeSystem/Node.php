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
        'title' => 'max:100',
        'user_id' => 'integer|required',
        'start_at' => 'date_format:Y-m-d',
        'end_at' => 'date_format:Y-m-d',
        'publish_at' => 'date_format:Y-m-d',
    );

    /**
     * @var array
     */
    protected $fieldList = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('node_type_id');
            $table->string('title', 250)->nullable()->default(null);
            $table->text('summary')->nullable()->default(null);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->index('user_id');
            $table->index('node_type_id');
            $table->index('title');
            $table->index('start_at');
            $table->index(array('start_at', 'end_at'), 'during');
            $table->index('published_at');
            $table->index('created_at');

        };
    }

    /**
     * Set the array of model attributes. No checking is done.
     *
     * @param  array  $attributes
     * @param  bool   $sync
     * @return void
     */
    public function setRawAttributes(array $attributes, $sync = false)
    {
        parent::setRawAttributes($attributes, $sync);
        $this->fieldList = $this->fieldsList();
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
     * @return array
     */
    public function languagesList()
    {
        return NodeLanguage::query()
            ->where('node_id', $this->id)
            ->orderBy('id')
            ->lists('language_name');
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

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->fieldList[$key])
             ? $this->fieldList[$key]
             : parent::__get($key);
    }

}
