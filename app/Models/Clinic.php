<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "clinic_name",
        "doctor",
        "specialization",
        "address",
        "medical_fees",
        "working_days",
        "working_times",
        "picture",
        "rate"
    ];



    public function bookvisit() {
        return $this->hasMany(BookVisit::class);
    }

    public function rates(){
        return $this->hasMany(ClinicRate::class);
    }
}
