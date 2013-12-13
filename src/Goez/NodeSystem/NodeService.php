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
        if (isset($input['field_name'])) {
            $fieldNames = $input['field_name'];
            unset($input['field_name']);
        }

        if (isset($input['field_type'])) {
            $fieldTypes = $input['field_type'];
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

        NodeFieldType::query()
            ->where('node_type_id', $nodeType->id)
            ->delete();

        foreach ($fieldTypes as $key => $fieldTypeId) {
            $name = $fieldNames[$key];
            NodeFieldType::create(array(
                'node_type_id'  => $nodeType->id,
                'field_type_id' => $fieldTypeId,
                'name'          => $name,
            ));
        }

        return true;
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function findNodeByName($name)
    {
        return NodeType::query()->where('name', $name)->first();
    }
}
