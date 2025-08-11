<?php

namespace Rivulet\Validation\Rules;

class String {
    public function passes($field, $value) {
        return is_string($value);
    }

    public function message($field) {
        return "{$field} must be a string";
    }
}