<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagamentoVale extends Model
{
    use HasFactory;

    protected $table = 'pagamento_vales';

    protected $fillable = [
        'pagamento_funcionario_id',
        'vale_id',
        'valor_pago'
    ];

    protected $casts = [
        'valor_pago' => 'decimal:2'
    ];

    public function pagamento()
    {
        return $this->belongsTo(PagamentoFuncionario::class, 'pagamento_funcionario_id');
    }

    public function vale()
    {
        return $this->belongsTo(Vale::class, 'vale_id');
    }
}