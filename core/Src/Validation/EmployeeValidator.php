<?php
namespace Src\Validation;

class EmployeeValidator extends Validator
{
    public static function make(array $data)
    {
        // Определяем правила для добавления сотрудника.
        $rules = [
            'lastname'   => 'required|min:2|max:255',
            'firstname'  => 'required|min:2|max:255',
            // Отчество не обязательно
            'dob'        => 'required',
            'position'   => 'required|max:255',
            'department' => 'required|numeric',
            'Username'     => 'required|min:3|max:255',
            'PasswordHash' => 'required|min:6'
        ];

        return new self($data, $rules);
    }
}