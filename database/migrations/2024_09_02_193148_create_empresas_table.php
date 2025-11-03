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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('empresa_id');
            $table->unsignedBigInteger('endereco_id');
            $table->string('fantasia', 255);
            $table->string('social', 255);
            $table->string('cnpj', 14);
            $table->string('ie', 20)->nullable();
            $table->string('email_contato', 255);
            $table->string('telefone_contato', 20);
            $table->string('telefone_reserva', 20)->nullable();
            $table->timestamps();

            $table->foreign('endereco_id')->references('endereco_id')->on('enderecos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign('empresas_endereco_id_foreign');
        });

        Schema::dropIfExists('empresas');
    }
};
