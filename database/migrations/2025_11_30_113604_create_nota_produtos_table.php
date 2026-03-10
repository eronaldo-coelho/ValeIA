<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota_produtos', function (Blueprint $table) {
            $table->id();
            $table->integer('nota_id');
            $table->string('produto');
            $table->string('categoria');
            $table->decimal('valor', 15, 2);
            $table->decimal('quantidade', 15, 3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_produtos');
    }
};