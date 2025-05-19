<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokBarangsTable extends Migration
{
    public function up()
    {
        Schema::create('stok_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('barang_id');
            $table->string('normal');
            $table->string('rusak');
            $table->unsignedInteger('satuan_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_barangs');
    }
}
