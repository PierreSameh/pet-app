<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
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
    ] ;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function petgallery(){
        return $this->hasMany(PetGallery::class);
    }
}
