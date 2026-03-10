<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('funcionario_id');
            $table->string('tipo');
            $table->string('status')->default('pendente');
            $table->text('motivo')->nullable();
            $table->longText('imagem')->nullable();
            
            $table->string('numero_documento')->nullable();
            $table->string('emitente')->nullable();
            $table->string('cnpj_cpf_emitente')->nullable();
            $table->date('data_emissao')->nullable();
            $table->decimal('valor_total_nota', 15, 2)->nullable();

            $table->string('estabelecimento')->nullable();
            $table->string('endereco')->nullable();
            $table->integer('probabilidade_nota_autentica')->nullable();
            $table->boolean('contem_bebida_alcoolica')->default(false);
            $table->json('itens_alcoolicos')->nullable();

            $table->timestamps();

            $table->index('admin_id');
            $table->index('funcionario_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};