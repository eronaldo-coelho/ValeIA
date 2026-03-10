<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos_planos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('plano_id');
            $table->string('external_reference')->nullable(); 
            $table->string('metodo_pagamento'); 
            $table->string('status')->default('pendente');
            $table->decimal('valor', 10, 2);
            $table->text('qr_code')->nullable();
            $table->text('qr_code_base64')->nullable();
            $table->string('boleto_url')->nullable();
            $table->string('boleto_linha_digitavel')->nullable();
            $table->dateTime('data_expiracao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos_planos');
    }
};