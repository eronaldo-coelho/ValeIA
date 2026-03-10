<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_credenciais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('token', 80)->unique();
            $table->string('name')->default('Token Principal');
            $table->json('permissoes'); // Array de permissões
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_credenciais');
    }
};