<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamsTable extends Migration
{
    public function up()
    {
        Schema::create('pinjams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjam_id')->nullable();
            $table->foreign('peminjam_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('praktik_id')->nullable();
            $table->foreign('praktik_id')->references('id')->on('praktiks')->restrictOnDelete();
            $table->string('kelas')->nullable();
            $table->string('tanggal_awal');
            $table->string('tanggal_akhir');
            $table->string('jam_awal')->nullable();
            $table->string('jam_akhir')->nullable();
            $table->string('matakuliah');
            $table->string('praktik')->nullable();
            $table->string('dosen')->nullable();
            $table->string('tempat')->nullable();
            $table->unsignedBigInteger('ruang_id')->nullable();
            $table->foreign('ruang_id')->references('id')->on('ruangs')->restrictOnDelete();
            $table->string('bahan')->nullable();
            $table->unsignedBigInteger('laboran_id')->nullable();
            $table->foreign('laboran_id')->references('id')->on('users')->restrictOnDelete();
            $table->enum('status', ['menunggu', 'proses', 'selesai']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pinjams');
    }
}
