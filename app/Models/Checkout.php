<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Checkout extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'product_id',
        'bump_offer_id',
        'upsell_offer_id',
        'total_amount',
        'status',
    ];

    public function user() { return $this->belongsTo(User::class); }

}
