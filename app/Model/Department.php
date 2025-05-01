<?php
namespace Model;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments'; // название таблицы в базе данных
    protected $primaryKey = 'DepartmentID';
    public $timestamps = false;

    protected $fillable = ['Name'];
}