<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "category_id",
        "name",
        "description",
        "type",
        "price",
        "quantity",
    ];

    public function store() {
        $this->belongsTo(Store::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function cartitems() {
        return $this->belongsTo(Cartitem::class);
    }

    public function productImages() {
        return $this->hasMany(ProductImage::class);
    }
}
