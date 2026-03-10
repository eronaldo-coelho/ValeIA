<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vale extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'nome'];
    
    // Relação inversa (opcional, mas bom ter)
public function funcionario()
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function vale()
    {
        return $this->belongsTo(Vale::class, 'vale_id');
    }
}