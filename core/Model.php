<?php
namespace Rivulet;

use Rivulet\Database\Connection;
use Rivulet\Database\QueryBuilder;
use Rivulet\Database\Relations\BelongsTo;
use Rivulet\Database\Relations\BelongsToMany;
use Rivulet\Database\Relations\HasMany;
use Rivulet\Database\Relations\HasOne;

abstract class Model
{
    protected $connection = 'default';
    protected $table      = '';
    protected $fillable   = [];
    protected $guarded    = ['*'];
    protected $hidden     = [];
    protected $columns    = [];
    protected $attributes = [];
    public $primaryKey    = 'id';

    /**
     * Initialize the model instance
     * @param array $attributes Initial model attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        if (empty($this->table)) {
            $this->table = strtolower(str_replace('\\', '_', get_class($this)));
        }
    }

    /**
     * Create and save a new model instance (static call).
     * Example: User::create(['name' => 'John']);
     */
    public static function create(array $data)
    {
        $model = new static(); // Create new instance
        $model->fill($data);   // Fill with data
        $model->save();        // Save to DB
        return $model;         // Return the model instance
    }

    /**
     * Get the database connection instance
     */
    protected function getConnection()
    {
        return Connection::get($this->connection);
    }

    /**
     * Create a new query builder instance
     */
    public function query()
    {
        $pdo = $this->getConnection();
        return new QueryBuilder($pdo, $this->table);
    }

    /**
     * Get all records from the model's table
     */
    public static function all()
    {
        $instance = new static();
        $data     = $instance->query()->get();
        return array_map(function ($row) {
            return new static($row);
        }, $data);
    }

    /**
     * Find a model by its primary key
     * @param mixed $id The primary key value
     */
    public static function find($id)
    {
        $instance = new static();
        $data     = $instance->query()->where($instance->primaryKey, $id)->first();
        return $data ? new static($data) : null;
    }

    /**
     * Add a basic where clause to the query
     * @param string $column The column name
     * @param string $operator The comparison operator
     * @param mixed $value The value to compare
     */
    public static function where($column, $operator, $value = null)
    {
        $instance = new static();
        return $instance->query()->where($column, $operator, $value);
    }

    /**
     * Save the model to the database
     */
    public function save()
    {
        $data = $this->getFillableData();
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

    /**
     * Delete the model from the database
     * @param bool $soft Whether to perform a soft delete
     */
    public function delete($soft = true)
    {
        if ($soft) {
            $this->query()->where($this->primaryKey, $this->getAttribute($this->primaryKey))->update(['deleted' => 1, 'deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            $this->query()->where($this->primaryKey, $this->getAttribute($this->primaryKey))->delete();
        }
    }

    /**
     * Determine if the model exists in the database
     */
    protected function exists()
    {
        return ! empty($this->attributes[$this->primaryKey]);
    }

    /**
     * Fill the model with an array of attributes
     * @param array $attributes The attributes to set
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Set a given attribute on the model
     * @param string $key The attribute name
     * @param mixed $value The attribute value
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get an attribute from the model
     * @param string $key The attribute name
     */
    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Get the fillable attributes as an array
     */
    protected function getFillableData()
    {
        $data = $this->attributes;
        if (! empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        } elseif (! empty($this->guarded)) {
            $data = array_diff_key($data, array_flip($this->guarded));
        }
        return $data;
    }

    /**
     * Convert the model instance to an array
     */
    public function toArray()
    {
        $data = $this->attributes;
        foreach ($this->hidden as $hide) {
            unset($data[$hide]);
        }
        return $data;
    }

    /**
     * Define a one-to-one relationship
     * @param string $related The related model class
     * @param string|null $foreignKey The foreign key name
     */
    public function hasOne($related, $foreignKey = null)
    {
        $foreignKey = $foreignKey ?? strtolower(BaseClassName(get_class($this))) . '_id';
        return new HasOne($related, $foreignKey)->getResults($this);
    }

    /**
     * Define a one-to-many relationship
     * @param string $related The related model class
     * @param string|null $foreignKey The foreign key name
     */
    public function hasMany($related, $foreignKey = null)
    {
        $foreignKey = $foreignKey ?? strtolower(BaseClassName(get_class($this))) . '_id';
        return new HasMany($related, $foreignKey)->getResults($this);
    }

    /**
     * Define an inverse one-to-one or many relationship
     * @param string $related The related model class
     * @param string|null $foreignKey The foreign key name
     */
    public function belongsTo($related, $foreignKey = null)
    {
        $foreignKey = $foreignKey ?? strtolower(BaseClassName($related)) . '_id';
        return new BelongsTo($related, $foreignKey)->getResults($this);
    }

    /**
     * Define a many-to-many relationship
     * @param string $related The related model class
     * @param string|null $pivotTable The pivot table name
     * @param string|null $foreignKey The foreign key name
     * @param string|null $relatedKey The related key name
     */
    public function belongsToMany($related, $pivotTable = null, $foreignKey = null, $relatedKey = null)
    {
        $pivotTable = $pivotTable ?? strtolower(BaseClassName(get_class($this))) . '_' . strtolower(BaseClassName($related));
        $foreignKey = $foreignKey ?? strtolower(BaseClassName(get_class($this))) . '_id';
        $relatedKey = $relatedKey ?? strtolower(BaseClassName($related)) . '_id';
        return new BelongsToMany($related, $pivotTable, $foreignKey, $relatedKey)->getResults($this);
    }
}
