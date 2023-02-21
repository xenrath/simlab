<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pinjams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjam_id')->nullable();
            $table->foreign('peminjam_id')->references('id')->on('users')->restrictOnDelete();
            $table->string('tanggal_awal')->nullable();
            $table->string('tanggal_akhir')->nullable();
            $table->string('jam_awal')->nullable();
            $table->string('jam_akhir')->nullable();
            $table->string('matakuliah')->nullable();
            $table->string('dosen')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('ruang_id')->nullable();
            $table->foreign('ruang_id')->references('id')->on('ruangs')->restrictOnDelete();
            $table->unsignedBigInteger('laboran_id')->nullable();
            $table->string('bahan')->nullable();
            $table->foreign('laboran_id')->references('id')->on('users')->restrictOnDelete();
            $table->enum('kategori', ['normal', 'estafet']);
            $table->enum('status', ['draft', 'menunggu', 'disetujui', 'selesai']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pinjams');
    }
}
