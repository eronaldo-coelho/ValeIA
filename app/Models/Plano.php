<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'desconto',
    ];

    protected $casts = [
        'descricao' => 'array',
        'valor' => 'decimal:2',
        'desconto' => 'integer',
    ];
}