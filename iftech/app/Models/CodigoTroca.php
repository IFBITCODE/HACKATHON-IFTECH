<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodigoTroca extends Model
{
    protected $table = 'codigos_troca';

    protected $fillable = [
        'empreendedor_id',
        'codigo',
        'moedas',
        'status',
        'user_id',
        'utilizado_em',
    ];

    protected $casts = [
        'moedas' => 'integer',
        'utilizado_em' => 'datetime',
    ];

    public function empreendedor()
    {
        return $this->belongsTo(Empreendedor::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
