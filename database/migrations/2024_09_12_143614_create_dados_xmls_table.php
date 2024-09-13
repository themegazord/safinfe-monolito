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
    Schema::create('dados_xml', function (Blueprint $table) {
      $table->id('dados_id');
      $table->unsignedBigInteger('xml_id');
      $table->unsignedBigInteger('empresa_id');
      $table->string('status', 15);
      $table->integer('modelo');
      $table->integer('serie');
      $table->integer('numeronf');
      $table->integer('numeronf_final')->nullable();
      $table->string('justificativa')->nullable();
      $table->dateTime('dh_emissao_evento');
      $table->string('chave', 45);
      $table->timestamps();

      $table->foreign('xml_id')->references('xml_id')->on('xmls');
      $table->foreign('empresa_id')->references('empresa_id')->on('empresas');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('dados_xml', function (Blueprint $table) {
      $table->dropForeign('dados_xml_xml_id_foreign');
      $table->dropForeign('dados_xml_empresa_id_foreign');
    });
    Schema::dropIfExists('dados_xml');
  }
};
