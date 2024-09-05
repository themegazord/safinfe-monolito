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
    Schema::create('contabilidades', function (Blueprint $table) {
      $table->id('contabildade_id');
      $table->unsignedBigInteger('endereco_id');
      $table->string('social',255);
      $table->string('cnpj',14);
      $table->string('telefone_corporativo',20);
      $table->string('email_corporativo',255);
      $table->string('email_contato',255);
      $table->string('telefone_contato',20);
      $table->string('telefone_reserva',20);
      $table->timestamps();

      $table->foreign('endereco_id')->references('endereco_id')->on('enderecos');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('contabilidades', function (Blueprint $table) {
      $table->dropForeign('contabilidades_endereco_id_foreign');
    });

    Schema::dropIfExists('contabilidades');
  }
};
