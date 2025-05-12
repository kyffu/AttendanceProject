<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    use HasFactory;
    protected $table = 'shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'late_tolerance'
    ];
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
