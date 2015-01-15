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
            && isset($input['display_name'])
            && isset($input['extra_settings'])
            && isset($input['sort_order'])
        ) {

            $fieldNames    = $input['field_name'];
            $displayNames  = $input['display_name'];
            $extraSettings = $input['extra_settings'];
            $sortOrders    = $input['sort_order'];

            foreach ($input['field_type'] as $key => $fieldTypeId) {
                $fieldTypes[] = array(
                    'field_type_id'  => $fieldTypeId,
                    'field_name'     => $fieldNames[$key],
                    'display_name'   => $displayNames[$key],
                    'extra_settings' => $extraSettings[$key],
                    'sort_order'     => $sortOrders[$key],
                );
            }

            unset($input['display_name']);
            unset($input['field_name']);
            unset($input['field_type']);
            unset($input['extra_settings']);
            unset($input['sort_order']);
        }

        $nodeType = null;

        if (isset($input['id'])) {
            $id       = $input['id'];
            $nodeType = NodeType::find($id);
        }

        if (null === $nodeType) {
            $nodeType = new NodeType();
        }

        if ($nodeType->fill($input)->save()) {
            $nodeType->saveFieldTypes($fieldTypes);
        }

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

    public static function getNodesByType($nodeTypeName, $grouped = false, $keyword = null, $lang = null)
    {
        $nodeT     = Node::$tableName;
        $nodeLangT = NodeLanguage::$tableName;
        $nodeType  = static::findNodeType($nodeTypeName);

        $nodeGroup      = new NodeGroup;
        $nodeGroupTable = $nodeGroup->getTable();
        $nodeIdColumn   = $nodeGroup->getNodeIdColumn();
        $groupIdColumn  = $nodeGroup->getGroupIdColumn();

        $query = Node::query();

        if ($grouped) {
            $query->selectRaw("GROUP_CONCAT(DISTINCT $nodeT.id) as ids");
            $query->groupBy($groupIdColumn);
        }

        $query->where("$nodeT.node_type_id", $nodeType->getKey());
        $query->whereNull("$nodeGroupTable.$groupIdColumn", 'and', $grouped);

        if (!empty($keyword)) {
            $query->where(function ($query) use ($nodeT, $keyword) {
                $query->where("$nodeT.title", 'LIKE', "%$keyword%");
                if (is_numeric($keyword)) {
                    $query->orWhere("$nodeT.id", (int) $keyword);
                }
            });
        }

        if (!empty($lang)) {
            $query->where("$nodeLangT.language_name", $lang);
        }

        $query->leftJoin($nodeGroupTable, "$nodeGroupTable.$nodeIdColumn", '=', "$nodeT.id");
        $query->join($nodeLangT, "$nodeLangT.node_id", '=', "$nodeT.id");

        $query->addSelect([
            "$nodeT.*",
            "$nodeLangT.language_name",
            "$nodeGroupTable.$groupIdColumn"
        ]);

        return $query;
    }

    public static function getByNodeIds(array $nodeIds)
    {
        $nodeT     = Node::$tableName;
        $nodeLangT = NodeLanguage::$tableName;

        $nodeGroup      = new NodeGroup;
        $nodeGroupTable = $nodeGroup->getTable();
        $nodeIdColumn   = $nodeGroup->getNodeIdColumn();
        $groupIdColumn  = $nodeGroup->getGroupIdColumn();

        $query = Node::query();

        $query->whereIn("$nodeT.id", $nodeIds);
        $query->orderBy("$nodeT.id", 'desc');

        $query->leftJoin($nodeLangT, "$nodeLangT.node_id", '=', "$nodeT.id");
        $query->leftJoin($nodeGroupTable, "$nodeGroupTable.$nodeIdColumn", '=', "$nodeT.id");

        $query->addSelect([
            "$nodeT.*",
            "$nodeLangT.language_name",
            "$nodeGroupTable.$groupIdColumn"
        ]);

        return $query;
    }
}
