<?php

namespace Rivulet\Database\Relations;

use Rivulet\Model;

class HasMany {
    protected $related;
    protected $foreignKey;

    public function __construct($related, $foreignKey) {
        $this->related = $related;
        $this->foreignKey = $foreignKey;
    }

    public function getResults(Model $parent) {
        $instance = new $this->related();
        $parentId = $parent->getAttribute($parent->primaryKey ?? 'id');
        return $instance->query()->where($this->foreignKey, $parentId)->get();
    }
}