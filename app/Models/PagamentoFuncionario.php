<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoFuncionario extends Model
{
    use HasFactory;

    protected $table = 'pagamentos_funcionarios';

    protected $fillable = [
        'admin_id',
        'funcionario_id',
        'imagem',
        'forma_pagamento',
        'valor',
        'data_pagamento',
        'funcionario_vale_id'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_pagamento' => 'date',
        'funcionario_vale_id' => 'array'
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getNomesValesAttribute()
    {
        if (empty($this->funcionario_vale_id)) {
            return '-';
        }

        $ids = $this->funcionario_vale_id;
        
        $nomes = Vale::whereIn('id', $ids)->pluck('nome')->toArray();

        return empty($nomes) ? '-' : implode(', ', $nomes);
    }
}