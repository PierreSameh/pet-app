<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicRate extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "clinic_id",
        "rate"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function clinc(){
        return $this->belongsTo(Clinic::class);
    }
}
