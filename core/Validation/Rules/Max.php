<?php

namespace Rivulet\Validation\Rules;

class Max {
    protected $max;

    public function __construct($max) {
        $this->max = (int) $max;
    }

    public function passes($field, $value) {
        if (is_numeric($value)) {
            return $value <= $this->max;
        } elseif (is_string($value)) {
            return strlen($value) <= $this->max;
        } elseif (is_array($value)) {
            return count($value) <= $this->max;
        }
        return false;
    }

    public function message($field) {
        return "{$field} must be at most {$this->max}";
    }
}