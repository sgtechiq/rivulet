<?php

namespace Rivulet\Validation;

use Exception;

class Validator {
    protected $errors = [];

    public function validate(array $data, array $rules) {
        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $ruleString);
            foreach ($ruleList as $rule) {
                [$ruleName, $param] = explode(':', $rule, 2) + [null, null];
                $ruleClass = $this->resolveRuleClass($ruleName);
                $ruleInstance = new $ruleClass($param);
                if (!$ruleInstance->passes($field, $value)) {
                    $this->errors[$field][] = $ruleInstance->message($field);
                }
            }
        }
        if (!empty($this->errors)) {
            throw new Exception(json_encode($this->errors));
        }
    }

    protected function resolveRuleClass($ruleName) {
        if ($ruleName === 'array') { // Alias for reserved word
            $ruleName = 'arr';
        }
        $coreClass = "\\Rivulet\\Validation\\Rules\\" . ucfirst($ruleName);
        if (class_exists($coreClass)) {
            return $coreClass;
        }
        $customClass = "\\App\\Rules\\" . ucfirst($ruleName);
        if (class_exists($customClass)) {
            return $customClass;
        }
        throw new Exception("Validation rule {$ruleName} not found");
    }

    public function errors() {
        return $this->errors;
    }
}