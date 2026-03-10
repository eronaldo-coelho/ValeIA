<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id')->unique(); // ID do chat (ex: 5513...@c.us)
            $table->string('phone_number');
            $table->unsignedBigInteger('admin_id')->nullable(); // Sem FK
            $table->unsignedBigInteger('funcionario_id')->nullable(); // Sem FK
            $table->string('step')->default('START'); // Controle de estado da conversa
            $table->string('pin', 4)->nullable(); // Senha de 4 dígitos
            $table->text('temp_data')->nullable(); // JSON para dados temporários (cadastro/notas)
            $table->integer('last_message_timestamp')->default(0); // Para evitar loops
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp');
    }
};