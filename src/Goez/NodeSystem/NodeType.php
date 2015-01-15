<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;
use ModelValidatingTrait;

class NodeType extends Eloquent
{
    use ModelValidatingTrait;

    public static $tableName = 'goez_node_types';
    protected $rules = [
        'type' => ['required', 'max:100'],
        'name' => ['required', 'max:100'],
        'description' => ['max:200'],
        'has_title' => ['in:y,n']
    ];
    public $timestamps = false;
    protected $table = 'goez_node_types';
    protected $guarded = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->string('type', 100); // Node 類型的英文名稱
            $table->string('name', 100); // Node 類型的中文名稱
            $table->string('description', 200)->nullable()->default(null); // 類型描述
            $table->enum('has_title', array('y', 'n'))->default('y'); // 是否要在 form 上出現標題欄位

            $table->index('type');
        };
    }

    /**
     * @param $fieldTypes
     */
    public function saveFieldTypes($fieldTypes)
    {
        $this->fieldTypes()->detach();

        foreach ($fieldTypes as $field) {
            $field['node_type_id'] = $this->id;
            NodeFieldType::create($field);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fieldTypes()
    {
        return $this->belongsToMany(
                    'Goez\NodeSystem\FieldType',
                    NodeFieldType::$tableName,
                    'node_type_id')
                ->withPivot('field_name', 'display_name', 'extra_settings', 'sort_order')
                ->orderBy(NodeFieldType::$tableName . '.sort_order')
                ->select(FieldType::$tableName . '.*',
                         NodeFieldType::$tableName . '.field_name',
                         NodeFieldType::$tableName . '.display_name',
                         NodeFieldType::$tableName . '.extra_settings',
                         NodeFieldType::$tableName . '.sort_order');
    }

    /**
     * @return bool
     */
    public function hasTitle()
    {
        return $this->has_title === 'y';
    }

}
