<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectWorkers extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'worker_name',
        'working_days',
        'salary_day',
        'total_salary',
        'is_mandor'
    ];
}
