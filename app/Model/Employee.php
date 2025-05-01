<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'LastName',
        'FirstName',
        'MiddleName',
        'BirthDate',
        'Address',
        'JobTitle',
        'DepartmentID'
    ];

    public $timestamps = false;
}