<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    protected $fillable = [
        'stripe_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'description',
    ];
}