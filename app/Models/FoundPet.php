<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundPet extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "type",
        "gender",
        "breed",
        "found_location",
        "found_time",
        "found_info",
     ];

    public function user() {
        return $this->belongsTo(User::class);
     }

     public functionfoundPetGallery() {
        return $this->hasMany(FoundPetGallery::class);
     }
}
