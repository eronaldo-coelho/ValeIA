<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlano extends Model
{
    use HasFactory;

    protected $table = 'users_planos';

    protected $fillable = [
        'admin_id',
        'plano_id',
        'data_inicio',
        'data_vencimento', // Novo campo
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_vencimento' => 'datetime', // Cast
    ];
}