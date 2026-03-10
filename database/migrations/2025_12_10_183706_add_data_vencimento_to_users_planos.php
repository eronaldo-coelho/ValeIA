<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users_planos', function (Blueprint $table) {
            $table->dateTime('data_vencimento')->nullable()->after('data_inicio');
        });
        
        // Atualiza registros existentes para terem 15 dias de prazo a partir do inicio
        DB::statement("UPDATE users_planos SET data_vencimento = DATE_ADD(data_inicio, INTERVAL 15 DAY) WHERE data_vencimento IS NULL");
    }

    public function down()
    {
        Schema::table('users_planos', function (Blueprint $table) {
            $table->dropColumn('data_vencimento');
        });
    }
};