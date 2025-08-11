<?php

namespace Rivulet\Validation\Rules;

class Integer {
    public function passes($field, $value) {
        return is_int($value);
    }

    public function message($field) {
        return "{$field} must be an integer";
    }
}