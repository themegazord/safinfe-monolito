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

    Schema::table('empresas', function (Blueprint $table) {
      $table->foreign('endereco_id')->references('endereco_id')->on('enderecos')->cascadeOnDelete();
    });
  }

  public function down(): void
  {
    Schema::table('empresas', function (Blueprint $table) {
      $table->foreign('endereco_id')->references('endereco_id')->on('enderecos')->restrictOnDelete();
    });
  }

};
