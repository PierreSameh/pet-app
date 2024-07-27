<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostPet extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "age",
        "type",
        "gender",
        "breed",
        "found",
        "lastseen_location",
        "lastseen_time",
        "lastseen_info",
     ];

     public function user() {
        return $this->belongsTo(User::class);
     }

     public function lostPetGallery() {
        return $this->hasMany(LostPetGallery::class);
     }
}
