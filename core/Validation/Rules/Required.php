<?php

namespace Rivulet\Validation\Rules;

class Required {
    public function passes($field, $value) {
        return !empty($value);
    }

    public function message($field) {
        return "{$field} is required";
    }
}