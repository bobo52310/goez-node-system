<?php

namespace Goez\NodeSystem;

use Goez\NodeSystem\NodeGroup as NodeGroup;

class NodeGroupService
{
    protected $nodeGroup;

    public function __construct(NodeGroup $nodeGroup)
    {
        $this->nodeGroup = $nodeGroup;
    }

    public function create(array $attributes)
    {
        $nodeGroup = $this->nodeGroup->create($attributes);

        return $nodeGroup;
    }

    public function batchCreate(array $values)
    {
        $create = $this->nodeGroup->insert($values);

        return $create;
    }

    public function batchUpdate($nodeIds, $groupId)
    {
        $query = $this->nodeGroup->query();

        $query->whereIn($this->nodeGroup->getNodeIdColumn(), $nodeIds);

        $updatedRows = $query->update([
            $this->nodeGroup->getGroupIdColumn() => $groupId
        ]);

        return !empty($update);
    }

    public function batchDestroy($nodeIds)
    {
        $query = $this->nodeGroup->query();

        $query->whereIn($this->nodeGroup->getNodeIdColumn(), $nodeIds);

        $delete = $query->delete();

        return !empty($delete);
    }

    public function findByNode($nodeId)
    {
        $query = $this->nodeGroup->query();

        $query->where($this->getNodeIdColumn(), $nodeId);

        $nodeGroup = $query->first();

        return $nodeGroup;
    }

    public function getByNodes($nodeIds)
    {
        $query = $this->nodeGroup->query();

        $query->whereIn($this->getNodeIdColumn(), (array) $nodeIds);

        $nodeGroups = $query->get();

        return $nodeGroups;
    }

    public function getByGroup($groupId)
    {
        $query = $this->nodeGroup->query();

        $query->where($this->getGroupIdColumn(), $groupId);

        $nodeGroups = $query->get();

        return $nodeGroups;
    }

    public function getNodeIdColumn()
    {
        return $this->nodeGroup->getNodeIdColumn();
    }

    public function getGroupIdColumn()
    {
        return $this->nodeGroup->getGroupIdColumn();
    }

    public function getTable()
    {
        return $this->nodeGroup->getTable();
    }
}
