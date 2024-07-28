<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundPetGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        "foundpet_id",
        "image",
    ];

    public function lostPet() {
        return $this->belongsTo(FoundPet::class);
    }
}
