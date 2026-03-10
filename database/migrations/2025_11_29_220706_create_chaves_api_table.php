<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chaves_api', function (Blueprint $table) {
            $table->id();
            $table->string('api_key')->unique();
            $table->boolean('ativo')->default(0); // 1 = ativa, 0 = inativa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chaves_api');
    }
};
