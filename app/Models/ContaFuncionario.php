<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaFuncionario extends Model
{
    use HasFactory;

    protected $table = 'contas_funcionarios';

    protected $fillable = [
        'admin_id',
        'funcionario_id',
        'tipo_pagamento',
        'chave_pix',
        'tipo_chave_pix',
        'banco',
        'agencia',
        'conta',
        'tipo_conta',
        'frequencia_pagamento',
        'dia_pagamento',
        'dia_semana',
        'principal'
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}