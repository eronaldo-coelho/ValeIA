<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->json('descricao');
            $table->decimal('valor', 10, 2);
            $table->unsignedTinyInteger('desconto')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};