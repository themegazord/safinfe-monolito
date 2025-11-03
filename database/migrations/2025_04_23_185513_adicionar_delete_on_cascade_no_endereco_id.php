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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->addCascadeDeleteToFK('empresas', 'endereco_id', 'enderecos', 'endereco_id', 'empresas_endereco_id_foreign');
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign('empresas_endereco_id_foreign');
            $table->foreign('endereco_id')->references('endereco_id')->on('enderecos');
        });
    }


    private function addCascadeDeleteToFK(string $table, string $column, string $references, string $referencedColumn, string $constraintName): void
    {
        // Tentar dropar a constraint antiga (se existir)
        try {
            DB::statement("ALTER TABLE $table DROP FOREIGN KEY $constraintName");
        } catch (\Exception) {
            // Ignorar erro se constraint não existir
        }

        // Adicionar a nova constraint com CASCADE DELETE
        DB::statement("ALTER TABLE $table ADD CONSTRAINT $constraintName
            FOREIGN KEY ($column) REFERENCES $references($referencedColumn) ON DELETE CASCADE");
    }
};
