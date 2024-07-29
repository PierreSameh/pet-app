<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPetGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        "marketpet_id",
        "image",
    ];

    public function marketpet() {
        return $this->belongsTo(MarketPet::class);
    }
}
