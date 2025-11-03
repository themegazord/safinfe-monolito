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
        Schema::table('empcont', function (Blueprint $table) {
            $table->dropForeign('empcont_contabilidade_id_foreign');
            $table->foreign('contabilidade_id')->references('contabilidade_id')->on('contabilidades')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empcont', function (Blueprint $table) {
            $table->dropForeign('empcont_contabilidade_id_foreign');
            $table->foreign('contabilidade_id')->references('contabilidade_id')->on('contabilidades');
        });
    }
};
