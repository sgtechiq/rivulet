<?php

namespace Rivulet\Validation\Rules;

class Arr {
    public function passes($field, $value) {
        return is_array($value);
    }

    public function message($field) {
        return "{$field} must be an array";
    }
}