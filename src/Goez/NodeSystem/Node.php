<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;
use ModelValidatingTrait;

class Node extends Eloquent
{
    use ModelValidatingTrait;

    public static $tableName = 'goez_nodes';
    protected $table = 'goez_nodes';
    protected $guarded = array();
    public $timestamps = false;
    protected $rules = [
        'user_id' => ['required'],
        'node_type_id' => ['required'],
        'title' => ['max:250']
    ];

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
            $table->integer('start_at')->unsigned()->nullable();
            $table->integer('end_at')->unsigned()->nullable();
            $table->integer('published_at')->unsigned()->nullable();
            $table->integer('created_at')->unsigned()->nullable();
            $table->integer('updated_at')->unsigned()->nullable();
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->hasMany(
            'Goez\NodeSystem\Field',
            'node_id')
            ->orderBy('sort_order');
    }

    /**
     * @return array
     */
    public function fieldsList()
    {
        return Field::query()->where('node_id', $this->id)
            ->orderBy('sort_order')
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
            NodeTag::$tableName,
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
            NodeLanguage::$tableName,
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

    /**
     * @param $fields
     */
    public function saveFields($fields)
    {
        foreach ($fields as $fieldName => $fieldValue) {
            $field = Field::query()->where('node_id', $this->id)
                ->where('field_name', $fieldName)
                ->first();

            if (null === $field) {
                $fieldData = array(
                    'node_id'    => $this->id,
                    'field_name' => $fieldName,
                    'body_value' => $fieldValue,
                    'created_at' => time(),
                );
                Field::create($fieldData);
            } else {
                $field->body_value = $fieldValue;
                $field->updated_at = time();
                $field->save();
            }
        }
    }

    /**
     * @param $langs
     */
    public function saveLanguages($langs)
    {
        $this->languages()->detach();

        foreach ($langs as $lang) {
            NodeLanguage::create(array(
                'node_id' => $this->id,
                'language_name'  => $lang,
            ));
        }
    }

    /**
     * @param $nodeTypeName
     * @param $colName
     * @return array
     */
    public static function getNodeListByType($nodeTypeName, $colName)
    {
        $nodeType = NodeService::findNodeType($nodeTypeName);

        return Node::query()->where('node_type_id', $nodeType->id)
            ->orderBy('id')
            ->lists($colName, 'id');
    }

    /**
     * Avoid to auto convert to date-time format.
     * @return int
     */
    public function freshTimestamp()
    {
        return time();
    }

    /**
     * @return array
     */
    public function getDates()
    {
        return array();
    }
}
