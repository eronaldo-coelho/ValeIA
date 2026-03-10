<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',        // Novo
        'document',    // Novo
        'birth_date',  // Novo
        'google_id',   // Novo
        'avatar',      // Novo
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date', // Cast para data
    ];

    public function plano()
    {
        // admin_id na tabela users_planos refere-se ao ID do usuário nesta tabela
        return $this->hasOne(UserPlano::class, 'admin_id');
    }
}