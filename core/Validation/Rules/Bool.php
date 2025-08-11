<?php

namespace Rivulet\Validation\Rules;

class Bool {
    public function passes($field, $value) {
        return is_bool($value);
    }

    public function message($field) {
        return "{$field} must be a boolean";
    }
}