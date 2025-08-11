<?php

namespace Rivulet\Database\Relations;

use Rivulet\Model;

class BelongsToMany {
    protected $related;
    protected $pivotTable;
    protected $foreignKey;
    protected $relatedKey;

    public function __construct($related, $pivotTable, $foreignKey, $relatedKey) {
        $this->related = $related;
        $this->pivotTable = $pivotTable;
        $this->foreignKey = $foreignKey;
        $this->relatedKey = $relatedKey;
    }

    public function getResults(Model $parent) {
        $instance = new $this->related();
        $parentId = $parent->getAttribute($parent->primaryKey ?? 'id');
        $query = $instance->query()
            ->join($this->pivotTable, "{$this->pivotTable}.{$this->relatedKey} = {$instance->table}.id")
            ->where("{$this->pivotTable}.{$this->foreignKey}", $parentId);
        return $query->get();
    }
}