<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuncionarioVale extends Model
{
    use HasFactory;

    protected $table = 'funcionarios_vales';

    protected $fillable = [
        'admin_id',
        'funcionario_id',
        'vale_id', // Mudou de 'nome' para 'vale_id'
        'valor',
        'periodicidade'
    ];

    protected $casts = [
        'valor' => 'decimal:2'
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    // Adicionar relacionamento com o tipo de vale
    public function tipo()
    {
        return $this->belongsTo(Vale::class, 'vale_id');
    }

    // Acessor para facilitar o uso na View: $funcionarioVale->nome
    public function getNomeAttribute()
    {
        return $this->tipo ? $this->tipo->nome : 'Desconhecido';
    }
}