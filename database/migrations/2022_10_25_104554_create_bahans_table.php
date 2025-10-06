<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBahansTable extends Migration
{
    public function up()
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->unsignedBigInteger('ruang_id')->nullable();
            $table->foreign('ruang_id')->references('id')->on('ruangs')->restrictOnDelete();
            $table->unsignedBigInteger('prodi_id');
            $table->foreign('prodi_id')->references('id')->on('prodis')->restrictOnDelete();
            $table->string('stok')->nullable();
            $table->string('satuan')->nullable();
            $table->string('satuan_pinjam');
            $table->unsignedBigInteger('satuan_id')->nullable();
            $table->foreign('satuan_id')->references('id')->on('satuans')->restrictOnDelete();
            $table->string('keterangan')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahans');
    }
}
