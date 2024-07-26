<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        "pet_id",
        "image",
    ];

    public function pets() {
        return $this->belongsTo(Pet::class);
    }
}
