<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowances extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'is_active',
        'quota'
    ];
}
