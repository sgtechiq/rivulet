<?php

namespace Rivulet\Validation\Rules;

class Regex {
    protected $pattern;

    public function __construct($pattern) {
        $this->pattern = $pattern;
    }

    public function passes($field, $value) {
        return preg_match($this->pattern, $value);
    }

    public function message($field) {
        return "{$field} does not match the pattern";
    }
}