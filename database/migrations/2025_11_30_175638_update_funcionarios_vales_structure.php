<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Adicionar a coluna vale_id
        Schema::table('funcionarios_vales', function (Blueprint $table) {
            $table->unsignedBigInteger('vale_id')->nullable()->after('funcionario_id');
            $table->foreign('vale_id')->references('id')->on('vales')->onDelete('cascade');
        });

        // 2. Script para Migrar os dados antigos (Para não perder quem já tem vale cadastrado)
        $oldVales = DB::table('funcionarios_vales')->select('nome', 'admin_id')->distinct()->get();

        foreach ($oldVales as $old) {
            if (!empty($old->nome)) {
                // Cria o vale na tabela nova se não existir
                $valeId = DB::table('vales')->insertGetId([
                    'nome' => $old->nome,
                    'admin_id' => $old->admin_id ?? 1, // Fallback se admin_id for null
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Atualiza a tabela antiga com o ID
                DB::table('funcionarios_vales')
                    ->where('nome', $old->nome)
                    ->update(['vale_id' => $valeId]);
            }
        }

        // 3. Remover a coluna nome antiga
        Schema::table('funcionarios_vales', function (Blueprint $table) {
            $table->dropColumn('nome');
            // Tornar vale_id obrigatório agora que migramos
            $table->unsignedBigInteger('vale_id')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('funcionarios_vales', function (Blueprint $table) {
            $table->string('nome')->nullable();
        });
        
        // Reverter migração seria complexo, mas aqui voltamos a estrutura básica
        Schema::table('funcionarios_vales', function (Blueprint $table) {
            $table->dropForeign(['vale_id']);
            $table->dropColumn('vale_id');
        });
    }
};