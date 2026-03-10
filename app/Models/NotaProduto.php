<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaProduto extends Model
{
    use HasFactory;

    protected $table = 'nota_produtos';

    protected $fillable = [
        'nota_id',
        'produto',
        'categoria',
        'valor',
        'quantidade'
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class);
    }
}