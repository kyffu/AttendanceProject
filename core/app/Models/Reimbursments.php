<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimbursments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'description', 'reimbursement_date', 'evidence_photo', 'status', 'validated_by', 'validated_at'
    ];

    public function validator()
    {
        return $this->belongsTo(User::class,'validated_by');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
