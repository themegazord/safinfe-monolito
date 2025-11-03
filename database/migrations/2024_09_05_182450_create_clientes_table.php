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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('cliente_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('nome');
            $table->string('email')->unique();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas');
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
        });

        Schema::dropIfExists('clientes');
    }
};
