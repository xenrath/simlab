<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatBarangsTable extends Migration
{
    public function up()
    {
        Schema::create('riwayat_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barangs')->restrictOnDelete();
            $table->string('barang_nama');
            $table->integer('jumlah');
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
        Schema::dropIfExists('riwayat_barangs');
    }
}
