<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empreendedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empreendedores';

    protected $fillable = [
        'user_id',
        'nome_fantasia',
        'razao_social',
        'cpf_cnpj',
        'category_id',
        'telefone',
        'email',
        'whatsapp',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'latitude',
        'longitude',
        'descricao',
        'horario_funcionamento',
        'acessivel',
        'recursos_acessibilidade',
        'status',
        'motivo_rejeicao',
        'selo_validado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}