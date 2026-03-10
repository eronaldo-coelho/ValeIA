<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('funcionario_id');
            $table->string('tipo_pagamento'); 
            $table->string('chave_pix')->nullable();
            $table->string('tipo_chave_pix')->nullable();
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta')->nullable();
            $table->string('tipo_conta')->nullable();
            $table->string('frequencia_pagamento');
            $table->integer('dia_pagamento')->nullable();
            $table->string('dia_semana')->nullable();
            $table->boolean('principal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas_funcionarios');
    }
};