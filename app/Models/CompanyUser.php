<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CompanyUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'company_users';

    protected $fillable = [
        'admin_id',
        'name',
        'email',
        'password',
        'role',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'permissions' => 'array',
        'password' => 'hashed',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}