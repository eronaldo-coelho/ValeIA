<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('nome');
            $table->string('cargo');
            $table->date('data_nascimento');
            $table->string('cpf')->unique();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->date('data_admissao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};