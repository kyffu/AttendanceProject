<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'desc',
        'start_date',
        'end_date',
        'foreman_id',
        'status',
        'validated_by',
        'validated_at',
    ];

    public function foreman()
    {
        return $this->belongsTo(User::class,'foreman_id','id');
    }
    public function validator()
    {
        return $this->belongsTo(User::class,'validated_by','id');
    }

}
