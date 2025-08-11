<?php

namespace Rivulet\Validation\Rules;

class File {
    protected $extensions;

    public function __construct($extensions) {
        $this->extensions = explode(',', $extensions);
    }

    public function passes($field, $value) {
        if (!is_array($value) || !isset($value['name'])) {
            return false;
        }
        $ext = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
        return in_array($ext, $this->extensions);
    }

    public function message($field) {
        return "{$field} must have extension: " . implode(', ', $this->extensions);
    }
}