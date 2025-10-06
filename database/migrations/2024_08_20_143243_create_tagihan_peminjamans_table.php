<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanPeminjamansTable extends Migration
{
    public function up()
    {
        Schema::create('tagihan_peminjamans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan_peminjamans');
    }
}
