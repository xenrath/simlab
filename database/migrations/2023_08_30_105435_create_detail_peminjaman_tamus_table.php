<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPeminjamanTamusTable extends Migration
{
    public function up()
    {
        Schema::create('detail_peminjaman_tamus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_peminjaman_tamus');
    }
}
