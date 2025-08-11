<?php

namespace Rivulet;

abstract class Model {
    protected $connection = 'default';
    protected $table = '';
    protected $fillable = [];
    protected $guarded = ['*'];
    protected $hidden = [];
    protected $columns = [];
    protected $attributes = [];
    public $primaryKey = 'id';

    public function __construct(array $attributes = []) {
        $this->fill($attributes);
        if (empty($this->table)) {
            $this->table = strtolower(str_replace('\\', '_', get_class($this)));
        }
    }

    protected function getConnection() {
        return \Rivulet\Database\Connection::get($this->connection);
    }

    public function query() {
        $pdo = $this->getConnection();
        return new \Rivulet\Database\QueryBuilder($pdo, $this->table);
    }

    public static function all() {
        $instance = new static();
        $data = $instance->query()->get();
        return array_map(function ($row) {
            return new static($row);
        }, $data);
    }

    public static function find($id) {
        $instance = new static();
        $data = $instance->query()->where($instance->primaryKey, $id)->first();
        return $data ? new static($data) : null;
    }

    public static function where($column, $operator, $value = null) {
        $instance = new static();
        $query = $instance->query()->where($column, $operator, $value);
        return $query;
    }

    public function save() {
        $data = $this->getFillableData();
        // Validate column types (simple)
        foreach ($this->columns as $column => $type) {
            if (isset($data[$column])) {
                // Basic type check (extend as needed)
            }
        }

        if ($this->exists()) {
            $this->query()->where($this->primaryKey, $this->getAttribute($this->primaryKey))->update($data);
        } else {
            $id = $this->query()->insert($data);
            $this->setAttribute($this->primaryKey, $id);
        }
        return $this;
    }

    public function delete($soft = true) {
        if ($soft) {
            $this->query()->where($this->primaryKey, $this->getAttribute($this->primaryKey))->update(['deleted' => 1, 'deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            $this->query()->where($this->primaryKey, $this->getAttribute($this->primaryKey))->delete();
        }
    }

    protected function exists() {
        return !empty($this->attributes[$this->primaryKey]);
    }

    public function fill(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key) {
        return $this->attributes[$key] ?? null;
    }

    protected function getFillableData() {
        $data = $this->attributes;
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        } elseif (!empty($this->guarded)) {
            $data = array_diff_key($data, array_flip($this->guarded));
        }
        return $data;
    }

    public function toArray() {
        $data = $this->attributes;
        foreach ($this->hidden as $hide) {
            unset($data[$hide]);
        }
        return $data;
    }

    // Relationships
    public function hasOne($related, $foreignKey = null) {
        $foreignKey = $foreignKey ?? strtolower(class_basename(get_class($this))) . '_id';
        $relation = new \Rivulet\Database\Relations\HasOne($related, $foreignKey);
        return $relation->getResults($this);
    }

    public function hasMany($related, $foreignKey = null) {
        $foreignKey = $foreignKey ?? strtolower(class_basename(get_class($this))) . '_id';
        $relation = new \Rivulet\Database\Relations\HasMany($related, $foreignKey);
        return $relation->getResults($this);
    }

    public function belongsTo($related, $foreignKey = null) {
        $foreignKey = $foreignKey ?? strtolower(class_basename($related)) . '_id';
        $relation = new \Rivulet\Database\Relations\BelongsTo($related, $foreignKey);
        return $relation->getResults($this);
    }

    public function belongsToMany($related, $pivotTable = null, $foreignKey = null, $relatedKey = null) {
        $pivotTable = $pivotTable ?? strtolower(class_basename(get_class($this))) . '_' . strtolower(class_basename($related));
        $foreignKey = $foreignKey ?? strtolower(class_basename(get_class($this))) . '_id';
        $relatedKey = $relatedKey ?? strtolower(class_basename($related)) . '_id';
        $relation = new \Rivulet\Database\Relations\BelongsToMany($related, $pivotTable, $foreignKey, $relatedKey);
        return $relation->getResults($this);
    }
}