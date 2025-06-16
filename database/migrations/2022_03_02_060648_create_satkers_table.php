<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satkers', function (Blueprint $table) {
            $table->id();
            $table->string('kd_satker');
            $table->string('kd_satker_str')->nullable();
            $table->string('nama_satker')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('fax')->nullable();
            $table->string('kodepos')->nullable();
            $table->string('status_satker')->nullable();
            $table->string('ket_satker')->nullable();
            $table->string('jenis_satker')->nullable();
            $table->string('kd_klpd')->nullable();
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
        Schema::dropIfExists('satkers');
    }
}
