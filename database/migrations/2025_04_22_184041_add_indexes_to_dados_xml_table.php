<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDadosXmlTable extends Migration
{
    public function up()
    {
        Schema::table('dados_xml', function (Blueprint $table) {
            // Índice em empresa_id
            $table->index('empresa_id', 'idx_empresa_id');

            // Índice em status
            $table->index('status', 'idx_status');

            // Índice composto em status, numeronf e dh_emissao_evento
            $table->index(['status', 'numeronf', 'dh_emissao_evento'], 'idx_status_numeronf_dhemissao');

            // Índice em numeronf
            $table->index('numeronf', 'idx_numeronf');

            // Índice em dh_emissao_evento
            $table->index('dh_emissao_evento', 'idx_dhemissao_evento');

            // Índice composto em modelo e serie
            $table->index(['modelo', 'serie'], 'idx_modelo_serie');
        });
    }

    public function down()
    {
        Schema::table('dados_xml', function (Blueprint $table) {
            // Remover os índices na reversão da migration
            $table->dropIndex('idx_empresa_id');
            $table->dropIndex('idx_status');
            $table->dropIndex('idx_status_numeronf_dhemissao');
            $table->dropIndex('idx_numeronf');
            $table->dropIndex('idx_dhemissao_evento');
            $table->dropIndex('idx_modelo_serie');
        });
    }
}
