<?php

namespace Rivulet\Validation\Rules;

class Ip {
    public function passes($field, $value) {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    public function message($field) {
        return "{$field} must be a valid IP address";
    }
}