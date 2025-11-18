<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $empresas = DB::table('empresas')
            ->select(DB::raw('count(*) as contagem_cnpj'))
            ->groupBy('cnpj')
            ->get()
            ->toArray();

        $contabilidades = DB::table('contabilidades')
            ->select(DB::raw('count(*) as contagem_cnpj'))
            ->groupBy('cnpj')
            ->get()
            ->toArray();

        if (! empty(array_filter($empresas, fn ($e) => $e->contagem_cnpj > 1)) || ! empty(array_filter($contabilidades, fn ($e) => $e->contagem_cnpj > 1))) {
            throw new Exception('Existem empresas ou contabilidades cadastradas com o mesmo CNPJ');
        }

        Schema::table('empresas', function (Blueprint $table) {
            $table->string('cnpj')->unique()->change();
        });

        Schema::table('contabilidades', function (Blueprint $table) {
            $table->string('cnpj')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropUnique('empresas_cnpj_unique');
        });

        Schema::table('contabilidades', function (Blueprint $table) {
            $table->dropUnique('contabilidades_cnpj_unique');
        });
    }
};
