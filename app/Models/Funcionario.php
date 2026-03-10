<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'nome',
        'cargo',
        'data_nascimento',
        'cpf',
        'email',
        'telefone',
        'data_admissao',
        'ativo'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_admissao' => 'date',
        'ativo' => 'boolean'
    ];

    public function vales()
    {
        return $this->hasMany(FuncionarioVale::class, 'funcionario_id');
    }

    public function contas()
    {
        return $this->hasMany(ContaFuncionario::class, 'funcionario_id');
    }
}