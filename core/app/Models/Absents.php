<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AbsentMasters;

class Absents extends Model
{
    use HasFactory;

    protected $table = 'absents';

    protected $fillable = [
        'absent_id',
        'start_date',
        'end_date',
        'evidence_file',
        'status',
        'created_by',
        'notes',
        'validated_by'
    ];
    public function master()
    {
        return $this->belongsTo(AbsentMasters::class,'absent_id');
    }
    public function user_created()
    {
        return $this->belongsTo(User::class,'created_by');
    }
    public function user_validated()
    {
        return $this->belongsTo(User::class,'validated_by');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

}
