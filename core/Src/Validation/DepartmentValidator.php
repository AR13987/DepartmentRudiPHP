<?php
namespace Src\Validation;

class DepartmentValidator extends Validator
{
    public static function make(array $data)
    {
        $rules = [
            'Name' => 'required|min:2|max:255'
        ];

        return new self($data, $rules);
    }
}
