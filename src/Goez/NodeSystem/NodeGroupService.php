<?php

namespace Goez\NodeSystem;

use Goez\NodeSystem\NodeGroup as NodeGroup;
use Goez\NodeSystem\NodeLanguage as NodeLanguage;

class NodeGroupService
{
    protected $nodeGroup;

    protected $nodeLanguage;

    public function __construct(NodeGroup $nodeGroup, NodeLanguage $nodeLanguage)
    {
        $this->nodeGroup    = $nodeGroup;
        $this->nodeLanguage = $nodeLanguage;
    }

    public function create($nodeId, $groupId)
    {
        if ($this->languageDuplicate([$nodeId], $groupId)) {
            return false;
        }

        return $nodeGroup = $this->nodeGroup->create([
            $this->getNodeIdColumn()  => $nodeId,
            $this->getGroupIdColumn() => $groupId
        ]);
    }

    public function update($nodeId, $groupId)
    {
        if ($this->languageDuplicate([$nodeId], $groupId)) {
            return false;
        }

        $nodeGroup = $this->findByNode($nodeId);

        if (empty($nodeGroup)) {
            return false;
        }

        return $nodeGroup->update([
            $this->getGroupIdColumn() => $groupId
        ]);
    }

    public function batchCreate(array $nodeIds, $groupId)
    {
        if ($this->languageDuplicate($nodeIds, $groupId)) {
            return false;
        }

        $nodeGroups = [];
        foreach ($nodeIds as $nodeId) {
            $nodeGroups[] = [
                $this->getNodeIdColumn()  => $nodeId,
                $this->getGroupIdColumn() => $groupId
            ];
        }

        $create = $this->nodeGroup->insert($nodeGroups);

        return $create;
    }

    public function batchUpdate(array $nodeIds, $groupId)
    {
        if ($this->languageDuplicate($nodeIds, $groupId)) {
            return false;
        }

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

    public function languageDuplicate(array $nodeIds, $groupId)
    {
        $nodeIdColumn  = $this->getNodeIdColumn();
        $groupIdColumn = $this->getGroupIdColumn();

        $allNodeIds = array_merge(
            $nodeIds,
            $this->getByGroup($groupId)->lists($nodeIdColumn)
        );

        $nodeLanguages = $this->nodeLanguage->query()->whereIn($nodeIdColumn, $allNodeIds)->lists('language_name');

        /** Check for duplicates. */
        return count($nodeLanguages) !== count(array_unique($nodeLanguages));
    }
}
