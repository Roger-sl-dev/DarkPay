<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $fillable = [
        'codigo', 'descricao', 'tipo', 'valor', 'ativo', 'validade'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'validade' => 'datetime',
    ];
}
