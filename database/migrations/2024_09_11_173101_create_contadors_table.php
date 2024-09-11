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
    Schema::create('contadores', function (Blueprint $table) {
      $table->id('contador_id');
      $table->unsignedBigInteger('usuario_id');
      $table->unsignedBigInteger('contabilidade_id');
      $table->string('nome',255);
      $table->string('email', 255);
      $table->string('cpf', 11);
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('usuario_id')->references('id')->on('users');
      $table->foreign('contabilidade_id')->references('contabilidade_id')->on('contabilidades');
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
    });
    Schema::dropIfExists('contadores');
  }
};
