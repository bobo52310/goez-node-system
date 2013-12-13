<?php

namespace Goez\NodeSystem;

use Illuminate\Support\Facades\Validator;

class NodeService
{
    /**
     * @param $input
     * @return bool
     */
    public static function saveNodeType($input)
    {
        $fieldTypes = array();
        if (isset($input['field_type']) && isset($input['field_name'])) {
            $fieldTypes = array_combine($input['field_name'], $input['field_type']);
            unset($input['field_name']);
            unset($input['field_type']);
        }

        $nodeType = null;

        if (isset($input['id'])) {
            $id = $input['id'];
            $nodeType = NodeType::find($id);
        }

        if (null === $nodeType) {
            $nodeType = new NodeType();
        }

        $nodeType->fill($input);
        $nodeType->save();
        $nodeType->saveFieldTypes($fieldTypes);

        return $nodeType;
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function findNodeTypeByName($name)
    {
        return NodeType::query()->where('name', $name)->first();
    }


}
