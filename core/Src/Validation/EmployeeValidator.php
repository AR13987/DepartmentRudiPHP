<?php
namespace Src\Validation;

class EmployeeValidator extends Validator
{
    public static function make(array $data)
    {
        // Определяем правила для добавления сотрудника.
        $rules = [
            'LastName'   => 'required|min:2|max:255',
            'FirstName'  => 'required|min:2|max:255',
            // Отчество не обязательно
            'BirthDate'        => 'required',
            'JobTitle'   => 'required|max:255',
            'Username'     => 'required|min:3|max:255',
            'PasswordHash' => 'required|min:6'
        ];

        return new self($data, $rules);
    }
}