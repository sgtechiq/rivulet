<?php

namespace Rivulet\Validation\Rules;

class FileSize {
    protected $maxSize;

    public function __construct($maxSize) {
        $this->maxSize = (int) $maxSize; // in bytes
    }

    public function passes($field, $value) {
        if (!is_array($value) || !isset($value['size'])) {
            return false;
        }
        return $value['size'] <= $this->maxSize;
    }

    public function message($field) {
        return "{$field} must be less than {$this->maxSize} bytes";
    }
}