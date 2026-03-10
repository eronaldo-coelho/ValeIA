<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoPlano extends Model
{
    use HasFactory;

    protected $table = 'pagamentos_planos';

    protected $fillable = [
        'admin_id',
        'plano_id',
        'external_reference',
        'metodo_pagamento',
        'status',
        'efetivado', // Novo campo
        'valor',
        'qr_code',
        'qr_code_base64',
        'boleto_url',
        'boleto_linha_digitavel',
        'data_expiracao'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_expiracao' => 'datetime',
        'efetivado' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class, 'plano_id');
    }
}