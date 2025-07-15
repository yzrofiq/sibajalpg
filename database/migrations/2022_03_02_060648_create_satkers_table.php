<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('satkers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kd_satker')->unique();
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
            $table->string('nama_klpd')->nullable();
            $table->string('jenis_klpd')->nullable();
            $table->string('kode_eselon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('satkers');
    }
};

