<?php

namespace Rivulet\Validation\Rules;

class Between {
    protected $min;
    protected $max;

    public function __construct($param) {
        [$this->min, $this->max] = explode(',', $param);
    }

    public function passes($field, $value) {
        if (is_numeric($value)) {
            return $value >= $this->min && $value <= $this->max;
        } elseif (is_string($value)) {
            $len = strlen($value);
            return $len >= $this->min && $len <= $this->max;
        }
        return false;
    }

    public function message($field) {
        return "{$field} must be between {$this->min} and {$this->max}";
    }
}