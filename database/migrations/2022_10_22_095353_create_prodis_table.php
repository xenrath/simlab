<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdisTable extends Migration
{
    public function up()
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tempat_id');
            $table->foreign('tempat_id')->references('id')->on('tempats')->restrictOnDelete();
            $table->string('nama');
            $table->string('singkatan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prodis');
    }
}
