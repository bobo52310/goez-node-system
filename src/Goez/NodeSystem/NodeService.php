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
        if (isset($input['field_type'])
                && isset($input['field_name'])
                && isset($input['display_name'])) {

            $fieldNames = $input['field_name'];
            $displayNames = $input['display_name'];
            foreach ($input['field_type'] as $key => $fieldTypeId) {
                $fieldTypes[] = array(
                    'field_type_id' => $fieldTypeId,
                    'field_name'    => $fieldNames[$key],
                    'display_name'  => $displayNames[$key],
                );
            }

            unset($input['display_name']);
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
     * @param $type
     * @return mixed
     */
    public static function findNodeType($type)
    {
        return NodeType::query()->where('type', $type)->first();
    }


}
