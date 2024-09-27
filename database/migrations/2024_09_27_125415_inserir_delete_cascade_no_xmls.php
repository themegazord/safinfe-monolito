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
    Schema::table('dados_xml', function (Blueprint $table) {
      $table->dropForeign('dados_xml_xml_id_foreign');
      $table->foreign('xml_id')->references('xml_id')->on('xmls')->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('dados_xml', function (Blueprint $table) {
      $table->dropForeign('dados_xml_xml_id_foreign');
      $table->foreign('xml_id')->references('xml_id')->on('xmls')->restrictOnDelete();
    });
  }
};
