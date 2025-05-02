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

    public function disciplines()
    {
        return $this->belongsToMany(
            \Model\Discipline::class,
            'employees_disciplines',
            'EmployeeID',
            'DisciplineID'
        );
    }
    public $timestamps = false;
}