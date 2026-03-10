<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vales', function (Blueprint $table) {
            $table->id();
            // Se o sistema for multi-empresa, mantenha o admin_id, senão pode tirar
            $table->integer('admin_id'); 
            $table->string('nome'); // Ex: Vale Alimentação, Vale Combustível
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vales');
    }
};