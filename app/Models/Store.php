<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "name",
        "picture",
    ];



    public function products(){
        return $this->hasMany(Product::class);
    }
}
