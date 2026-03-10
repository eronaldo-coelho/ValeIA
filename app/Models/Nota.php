<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';

    protected $fillable = [
        'admin_id',
        'funcionario_id',
        'vale_id',
        'tipo',
        'status',
        'motivo',
        'imagem',
        'numero_documento',
        'emitente',
        'cnpj_cpf_emitente',
        'data_emissao',
        'valor_total_nota',
        'estabelecimento',
        'endereco',
        'probabilidade_nota_autentica',
        'contem_bebida_alcoolica',
        'itens_alcoolicos'
    ];

    protected $casts = [
        'contem_bebida_alcoolica' => 'boolean',
        'itens_alcoolicos' => 'array',
        'data_emissao' => 'date'
    ];

    public function produtos()
    {
        return $this->hasMany(NotaProduto::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function vale()
    {
        return $this->belongsTo(Vale::class, 'vale_id');
    }
}