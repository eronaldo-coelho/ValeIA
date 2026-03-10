<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChaveApi extends Model
{
    protected $table = 'chaves_api';

    protected $fillable = [
        'api_key',
        'ativo'
    ];

    public static function getActiveKey()
    {
        return self::where('ativo', 1)->first();
    }
}
