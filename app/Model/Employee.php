<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $primaryKey = 'EmployeeID';

    public $incrementing = true;
    protected $fillable = [
        'LastName',
        'FirstName',
        'MiddleName',
        'BirthDate',
        'Address',
        'Gender',
        'JobTitle',
        'DepartmentID'
    ];

    public $timestamps = false;
}