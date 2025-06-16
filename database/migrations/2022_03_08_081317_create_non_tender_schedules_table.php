<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNonTenderSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_tender_schedules', function (Blueprint $table) {
            $table->id();
            $table->string("kd_nontender")->nullable();
            $table->string("kd_tahapan")->nullable();
            $table->string("nama_tahapan")->nullable();
            $table->string("kd_akt")->nullable();
            $table->string("nama_akt")->nullable();
            $table->timestamp("tanggal_awal")->nullable();
            $table->timestamp("tanggal_akhir")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('non_tender_schedules');
    }
}
