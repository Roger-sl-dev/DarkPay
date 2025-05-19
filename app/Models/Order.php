<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'cliente_nome', 'status',
        'endereco_rua', 'endereco_numero', 'endereco_bairro',
        'endereco_cidade', 'endereco_estado', 'endereco_cep'
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'order_produto')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}
