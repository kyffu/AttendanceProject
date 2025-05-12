<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Absents;

class AbsentMasters extends Model
{
    use HasFactory;

    protected $table = 'absent_masters';
    protected $fillable = [
        'name',
        'quota',
        'evc'
    ];

    public function absent()
    {
        return $this->hasMany(Absents::class);
    }
}
