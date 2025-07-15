<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderDataTableFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('tender_data', function (Blueprint $table) {
        $table->id();
        $table->string('kd_tender')->nullable();
        $table->string('nama_paket')->nullable();
        $table->string('status_tender')->nullable();
        $table->string('category')->nullable(); // jika kamu isi manual
        $table->string('nama_satker')->nullable();
        $table->string('kd_satker')->nullable();
        $table->decimal('pagu', 20, 2)->nullable();
        $table->decimal('hps', 20, 2)->nullable();
        $table->decimal('nilai_kontrak', 20, 2)->nullable();
        $table->year('tahun')->nullable();
        $table->string('sumber_api')->nullable();
        $table->timestamps(); // created_at & updated_at
    });
}

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_data_table_fix');
    }
}
