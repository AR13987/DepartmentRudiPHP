<?php
namespace Src\Validation;

class UserValidator extends Validator
{
    public static function make(array $data)
    {
        $rules = [
            'Username'     => 'required|min:3|max:255',
            'PasswordHash' => 'required|min:6'
        ];

        return new self($data, $rules);
    }
}

