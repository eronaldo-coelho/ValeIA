<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
    use HasFactory;

    protected $table = 'whatsapp';

    protected $fillable = [
        'chat_id',
        'phone_number',
        'admin_id',
        'funcionario_id',
        'step',
        'pin',
        'temp_data',
        'last_message_timestamp'
    ];

    protected $casts = [
        'temp_data' => 'array',
    ];
}