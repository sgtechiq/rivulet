<?php

namespace Rivulet\Validation\Rules;

class Alpha {
    public function passes($field, $value) {
        return ctype_alpha($value);
    }

    public function message($field) {
        return "{$field} must contain only letters";
    }
}