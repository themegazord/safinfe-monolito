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
        Schema::table('contadores', function (Blueprint $table) {
            $table->dropForeign('contadores_usuario_id_foreign');
            $table->dropForeign('contadores_contabilidade_id_foreign');

            $table->foreign('usuario_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('contabilidade_id')->references('contabilidade_id')->on('contabilidades')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contadores', function (Blueprint $table) {
            $table->dropForeign('contadores_usuario_id_foreign');
            $table->dropForeign('contadores_contabilidade_id_foreign');

            $table->foreign('usuario_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('contabilidade_id')->references('contabilidade_id')->on('contabilidades')->restrictOnDelete();
        });
    }
};
