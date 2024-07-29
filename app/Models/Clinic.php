<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "doctor",
        "specialization",
        "address",
        "medical_fees",
        "working_days",
        "working_times",
        "picture",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
