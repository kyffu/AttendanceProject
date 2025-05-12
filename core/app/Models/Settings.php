<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $fillable = ['key', 'value','placeholder'];

    public static function getVal($key)
    {
        return optional(self::where('key', $key)->first())->value;
    }
}
