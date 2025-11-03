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
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_usuario_id_foreign');
            $table->dropForeign('clientes_empresa_id_foreign');

            $table->foreign('usuario_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_usuario_id_foreign');
            $table->dropForeign('clientes_empresa_id_foreign');

            $table->foreign('usuario_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->restrictOnDelete();
        });
    }
};
