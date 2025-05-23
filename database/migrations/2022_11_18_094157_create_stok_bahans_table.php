<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBahansTable extends Migration
{
    public function up()
    {
        Schema::create('stok_bahans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('bahan_id');
            $table->string('stok');
            $table->unsignedInteger('satuan_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_bahans');
    }
}
