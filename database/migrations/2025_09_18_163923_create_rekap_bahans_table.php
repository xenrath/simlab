<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekapBahansTable extends Migration
{
    public function up()
    {
        Schema::create('rekap_bahans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bahan_id');
            $table->foreign('bahan_id')->references('id')->on('bahans')->restrictOnDelete();
            $table->string('bahan_nama');
            $table->unsignedBigInteger('prodi_id');
            $table->foreign('prodi_id')->references('id')->on('prodis')->restrictOnDelete();
            $table->string('prodi_nama');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->enum('status', ['masuk', 'keluar']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekap_bahans');
    }
}
