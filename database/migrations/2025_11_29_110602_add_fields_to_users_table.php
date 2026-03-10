<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('type')->default('PF'); // PF ou PJ
        $table->string('document')->nullable()->unique(); // CPF ou CNPJ
        $table->date('birth_date')->nullable();
        $table->string('google_id')->nullable();
        $table->string('avatar')->nullable();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['type', 'document', 'birth_date', 'google_id', 'avatar']);
    });
}
};
