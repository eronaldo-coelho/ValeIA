<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;

    protected $table = 'auditorias';

    protected $fillable = [
        'admin_id',
        'user_id',
        'log',
        'created_at',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class, 'user_id');
    }
}