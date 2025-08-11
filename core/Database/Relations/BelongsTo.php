<?php

namespace Rivulet\Database\Relations;

use Rivulet\Model;

class BelongsTo {
    protected $related;
    protected $foreignKey;

    public function __construct($related, $foreignKey) {
        $this->related = $related;
        $this->foreignKey = $foreignKey;
    }

    public function getResults(Model $child) {
        $instance = new $this->related();
        $ownerKey = $child->getAttribute($this->foreignKey);
        return $instance->query()->where('id', $ownerKey)->first();
    }
}