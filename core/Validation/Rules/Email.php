<?php

namespace Rivulet\Validation\Rules;

class Email {
    public function passes($field, $value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function message($field) {
        return "{$field} must be a valid email";
    }
}