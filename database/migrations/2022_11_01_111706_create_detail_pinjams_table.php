<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPinjamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pinjams', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pinjam_id');
            $table->unsignedInteger('barang_id');
            $table->string('jumlah');
            $table->unsignedInteger('satuan_id');
            $table->string('normal')->nullable();
            $table->string('rusak')->nullable();
            $table->string('hilang')->nullable();
            $table->unsignedInteger('kelompok_id')->nullable();
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
        Schema::dropIfExists('detail_pinjams');
    }
}
