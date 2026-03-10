<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios_vales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('funcionario_id')->constrained('funcionarios')->onDelete('cascade');
            $table->string('nome');
            $table->decimal('valor', 10, 2);
            $table->enum('periodicidade', ['diario', 'semanal', 'mensal']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios_vales');
    }
};