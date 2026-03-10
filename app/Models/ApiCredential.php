<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    use HasFactory;

    protected $table = 'api_credenciais';

    protected $fillable = [
        'admin_id',
        'token',
        'name',
        'permissoes',
    ];

    protected $casts = [
        'permissoes' => 'array',
    ];
}