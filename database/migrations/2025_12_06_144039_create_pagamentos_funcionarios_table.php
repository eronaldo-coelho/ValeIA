<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos_funcionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('funcionario_id');
            $table->longText('imagem')->nullable();
            $table->string('forma_pagamento');
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento');
            $table->json('funcionario_vale_id')->nullable();
            $table->timestamps();

            $table->foreign('funcionario_id')->references('id')->on('funcionarios')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos_funcionarios');
    }
};