<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;

class NodeType extends Eloquent
{
    protected $table = 'goez_node_types';
    public $timestamps = false;
    protected $guarded = array();

    public static $rules = array(
        'name' => 'max:100|required',
        'type' => 'max:100|required',
        'description' => 'max:200',
        'has_title' => 'in:y,n',
    );

    public function saveFieldTypes($fieldTypes)
    {

    }
}
