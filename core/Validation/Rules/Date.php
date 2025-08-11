<?php

namespace Rivulet\Validation\Rules;

class Date {
    protected $format;

    public function __construct($format = null) {
        $this->format = $format ?? 'Y-m-d';
    }

    public function passes($field, $value) {
        $d = \DateTime::createFromFormat($this->format, $value);
        return $d && $d->format($this->format) === $value;
    }

    public function message($field) {
        return "{$field} must be a valid date in format {$this->format}";
    }
}