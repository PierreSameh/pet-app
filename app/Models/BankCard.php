<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCard extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "cardholder_name",
        "expiry_date",
        "encrypted_cvv",
    ] ;

    public function user(){
        return $this->belongsTo(User::class);
    }
}
