<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPositions extends Model
{
    use HasFactory;
    protected $table = 'job_positions';
    protected $fillable = ['description','title','salaries_id','role_id'];

    public function salaries()
    {
        return $this->belongsTo(Salaries::class);
    }
}
