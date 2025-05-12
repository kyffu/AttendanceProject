<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'time_in', 'time_out','approved','approved_out','photo_path','photo_path_out'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
