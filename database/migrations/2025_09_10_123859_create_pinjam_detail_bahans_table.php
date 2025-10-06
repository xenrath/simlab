<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatBahansTable extends Migration
{
    public function up()
    {
        Schema::create('pinjam_detail_bahans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pinjam_id');
            $table->foreign('pinjam_id')->references('id')->on('pinjams')->restrictOnDelete();
            $table->unsignedBigInteger('bahan_id');
            $table->foreign('bahan_id')->references('id')->on('bahans')->restrictOnDelete();
            $table->string('bahan_nama');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_bahans');
    }
}
