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
    Schema::create('enderecos', function (Blueprint $table) {
      $table->id('endereco_id');
      $table->string('rua', 155);
      $table->integer('numero');
      $table->string('cep', 8);
      $table->string('bairro', 155);
      $table->string('complemento', 255)->nullable();
      $table->string('cidade', 155);
      $table->string('estado', 2);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('enderecos');
  }
};
