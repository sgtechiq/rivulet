<?php

namespace Rivulet\Validation\Rules;

class Alphanum {
    public function passes($field, $value) {
        return ctype_alnum($value);
    }

    public function message($field) {
        return "{$field} must contain only letters and numbers";
    }
}