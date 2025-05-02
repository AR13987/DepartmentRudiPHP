<?php
namespace Src\Validation;

class Validator
{
    protected $data;
    protected $rules;
    protected $errors = [];

    /**
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->data  = $data;
        $this->rules = $rules;
        $this->validate();
    }

    protected function validate()
    {
        // Для каждого поля применяем правила
        foreach ($this->rules as $field => $rules) {
            $rulesArr = explode('|', $rules);
            $value = isset($this->data[$field]) ? trim($this->data[$field]) : null;
            foreach ($rulesArr as $rule) {
                // Правило может быть с параметром, например: min:3
                $params = null;
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $params) = explode(':', $rule, 2);
                } else {
                    $ruleName = $rule;
                }
                $method = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $params);
                }
            }
        }
    }

    protected function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    // Проверка на обязательность
    protected function validateRequired($field, $value, $params = null)
    {
        if ($value === null || $value === '') {
            $this->addError($field, ucfirst($field) . " обязательно для заполнения.");
        }
    }

    // Проверка на числовое значение
    protected function validateNumeric($field, $value, $params = null)
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, ucfirst($field) . " должно быть числовым.");
        }
    }

    // Минимальная длина (например, min:3)
    protected function validateMin($field, $value, $params)
    {
        if (!empty($value) && strlen($value) < intval($params)) {
            $this->addError($field, ucfirst($field) . " должно содержать минимум " . $params . " символов.");
        }
    }

    // Максимальная длина (например, max:255)
    protected function validateMax($field, $value, $params)
    {
        if (!empty($value) && strlen($value) > intval($params)) {
            $this->addError($field, ucfirst($field) . " должно содержать не более " . $params . " символов.");
        }
    }

    // Проверка на email (на будущее)
    protected function validateEmail($field, $value, $params = null)
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, ucfirst($field) . " должен быть действительным email адресом.");
        }
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }
}
