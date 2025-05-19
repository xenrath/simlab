<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSatuansTable extends Migration
{
    public function up()
    {
        Schema::create('satuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('singkatan');
            $table->string('kali');
            $table->enum('kategori', ['volume', 'berat', 'barang', 'bahan']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('satuans');
    }
}
