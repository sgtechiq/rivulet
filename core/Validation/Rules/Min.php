<?php

namespace Rivulet\Validation\Rules;

class Min {
    protected $min;

    public function __construct($min) {
        $this->min = (int) $min;
    }

    public function passes($field, $value) {
        if (is_numeric($value)) {
            return $value >= $this->min;
        } elseif (is_string($value)) {
            return strlen($value) >= $this->min;
        } elseif (is_array($value)) {
            return count($value) >= $this->min;
        }
        return false;
    }

    public function message($field) {
        return "{$field} must be at least {$this->min}";
    }
}