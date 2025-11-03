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
        Schema::create('empcont', function (Blueprint $table) {
            $table->id('empcont_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('contabilidade_id');
            $table->timestamps();

            $table->foreign('empresa_id')->references('empresa_id')->on('empresas');
            $table->foreign('contabilidade_id')->references('contabilidade_id')->on('contabilidades');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empcont', function (Blueprint $table) {
            $table->dropForeign('empcont_empresa_id_foreign');
            $table->dropForeign('empcont_contabilidade_id_foreign');
        });

        Schema::dropIfExists('empcont');
    }
};
