<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamento_vales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pagamento_funcionario_id');
            $table->unsignedBigInteger('vale_id');
            $table->decimal('valor_pago', 10, 2);
            $table->timestamps();

            $table->foreign('pagamento_funcionario_id')->references('id')->on('pagamentos_funcionarios')->onDelete('cascade');
            $table->foreign('vale_id')->references('id')->on('vales')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamento_vales');
    }
};