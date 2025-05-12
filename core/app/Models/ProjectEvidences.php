<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectEvidences extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'photo_path',
        'description'
    ];
}
