<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function store() {
        $this->belongsTo(Store::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function productImages() {
        return $this->hasMany(ProductImage::class);
    }
}
