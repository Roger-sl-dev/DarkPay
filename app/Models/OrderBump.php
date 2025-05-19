<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderBump extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'active',
    ];
    //
}
