<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "clinic_id",
        "time",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function clinics() {
        return $this->belongsTo(Clinic::class);
    }
}
