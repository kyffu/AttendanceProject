<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowancePayrolls extends Model
{
    use HasFactory;
    protected $fillable = [
        'period',
        'allow_id',
        'employee_id'
    ];

    public function allowance()
    {
        return $this->belongsTo(Allowances::class,'allow_id','id');
    }
}
