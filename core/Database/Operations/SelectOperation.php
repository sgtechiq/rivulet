<?php
namespace Rivulet\Database\Operations;

trait SelectOperation
{
    protected $select  = '*';
    protected $where   = [];
    protected $orWhere = [];
    protected $limit;
    protected $offset;
    protected $orderBy = [];
    protected $joins   = [];
    protected $groupBy = [];
    protected $having  = [];

    protected function reset()
    {
        $this->select  = '*';
        $this->where   = [];
        $this->orWhere = [];
        $this->limit   = null;
        $this->offset  = 0;
        $this->orderBy = [];
        $this->joins   = [];
        $this->groupBy = [];
        $this->having  = [];
    }

    public function select($columns = '*')
    {
        $this->select = is_array($columns) ? implode(', ', $columns) : $columns;
        return $this;
    }

    public function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value    = $operator;
            $operator = '=';
        }
        $this->where[] = [$column, $operator, $value];
        return $this;
    }

    public function orWhere($column, $operator, $value = null)
    {
        if ($value === null) {
            $value    = $operator;
            $operator = '=';
        }
        $this->orWhere[] = [$column, $operator, $value];
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit  = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy[] = "{$column} {$direction}";
        return $this;
    }

    public function join($table, $on, $type = 'INNER')
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$on}";
        return $this;
    }

    public function groupBy($columns)
    {
        $this->groupBy = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    public function having($column, $operator, $value = null)
    {
        if ($value === null) {
            $value    = $operator;
            $operator = '=';
        }
        $this->having[] = [$column, $operator, $value];
        return $this;
    }

    public function get()
    {
        $query = "SELECT {$this->select} FROM {$this->table}";
        if (! empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
        $whereClause = $this->buildWhere();
        if ($whereClause) {
            $query .= ' WHERE ' . $whereClause;
        }
        if (! empty($this->groupBy)) {
            $query .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }
        if (! empty($this->having)) {
            $query .= ' HAVING ' . $this->buildHaving();
        }
        if (! empty($this->orderBy)) {
            $query .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        if ($this->limit) {
            $query .= " LIMIT {$this->limit}";
            if ($this->offset) {
                $query .= " OFFSET {$this->offset}";
            }
        }
        $stmt = $this->pdo->prepare($query);
        $this->bindWhere($stmt);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $this->reset();
        return $results;
    }

    public function first()
    {
        $results = $this->limit(1)->get();
        return $results[0] ?? null;
    }

    protected function buildWhere()
    {
        $conditions   = $this->buildConditions($this->where);
        $orConditions = $this->buildConditions($this->orWhere);
        $where        = '';
        if ($conditions) {
            $where = '(' . implode(' AND ', $conditions) . ')';
        }
        if ($orConditions) {
            $where .= ($where ? ' OR ' : '') . '(' . implode(' OR ', $orConditions) . ')';
        }
        return $where;
    }

    protected function buildHaving()
    {
        return $this->buildConditions($this->having, ' AND ');
    }

    protected function buildConditions(array $clauses, $glue = ' AND ')
    {
        $conditions = [];
        foreach ($clauses as [$column, $operator, $value]) {
            if ($operator === 'IN' || $operator === 'NOT IN') {
                $placeholders = implode(', ', array_fill(0, count((array) $value), '?'));
                $conditions[] = "{$column} {$operator} ({$placeholders})";
            } else {
                $conditions[] = "{$column} {$operator} ?";
            }
        }
        return $conditions;
    }

    protected function getWhereValues()
    {
        return $this->getConditionValues($this->where) + $this->getConditionValues($this->orWhere) + $this->getConditionValues($this->having);
    }

    protected function getConditionValues(array $clauses)
    {
        $values = [];
        foreach ($clauses as $clause) {
            $value = $clause[2];
            if (is_array($value)) {
                $values = array_merge($values, $value);
            } else {
                $values[] = $value;
            }
        }
        return $values;
    }

    protected function bindWhere($stmt, $offset = 0)
    {
        $i = $offset + 1;
        foreach ($this->getWhereValues() as $value) {
            $stmt->bindValue($i++, $value);
        }
    }
    public function getCount()
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table}";
        if (! empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
        $whereClause = $this->buildWhere();
        if ($whereClause) {
            $query .= ' WHERE ' . $whereClause;
        }
        if (! empty($this->groupBy)) {
            $query .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }
        if (! empty($this->having)) {
            $query .= ' HAVING ' . $this->buildHaving();
        }
        $stmt = $this->pdo->prepare($query);
        $this->bindWhere($stmt);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->reset();
        return $result['count'] ?? 0;
    }

}
