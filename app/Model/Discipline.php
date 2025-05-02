<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $table = 'disciplines';
    protected $primaryKey = 'DisciplineID';

    public $timestamps = false;

    protected $fillable = ['Name'];

    public function employees()
    {
        return $this->belongsToMany(
            \Model\Employee::class,
            'employees_disciplines',
            'DisciplineID',
            'EmployeeID'
        );
    }
}