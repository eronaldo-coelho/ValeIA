<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_planos', function (Blueprint $table) {
            $table->id();
            
            // Colunas de ID sem restrição de chave estrangeira
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('plano_id');
            
            // Data e Hora juntos
            $table->dateTime('data_inicio');
            
            $table->timestamps(); // Cria created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_planos');
    }
};