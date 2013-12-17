<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class NodeType extends Eloquent
{
    public static $tableName = 'goez_node_types';
    public static $rules = array(
        'name' => 'max:100|required',
        'type' => 'max:100|required',
        'description' => 'max:200',
        'has_title' => 'in:y,n',
    );
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
                    'goez_node_field_types',
                    'node_type_id')
                ->withPivot('id', 'field_name', 'display_name');
    }

}
