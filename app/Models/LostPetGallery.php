<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostPetGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        "lostpet_id",
        "image",
    ];

    public function lostPet() {
        return $this->belongsTo(LostPet::class);
    }
}
