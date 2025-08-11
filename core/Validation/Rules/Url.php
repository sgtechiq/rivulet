<?php

namespace Rivulet\Validation\Rules;

class Url {
    public function passes($field, $value) {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public function message($field) {
        return "{$field} must be a valid URL";
    }
}