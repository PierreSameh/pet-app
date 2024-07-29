<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPet extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "name",
        "age",
        "type",
        "gender",
        "breed",
        "picture",
        "for_adoption",
        "price",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function marketpetgallery() {
        return $this->hasMany(MarketPetGallery::class);
    }
}
