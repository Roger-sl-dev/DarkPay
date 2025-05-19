<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
      protected $fillable = [
        'nome',
        'descricao',
        'tipo_produto',
        'url_da_pl',
        'tipo_entrega',
        'informacoes_email',
        'imagem',
        'garantia',
    ];

    public function orders()
{
    return $this->belongsToMany(Order::class, 'order_produto')
                ->withPivot('quantidade')
                ->withTimestamps();
}
}
