<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertanyaanKuesionersTable extends Migration
{
    public function up()
    {
        Schema::create('pertanyaan_kuesioners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kuesioner_id');
            $table->foreign('kuesioner_id')->references('id')->on('kuesioners')->restrictOnDelete();
            $table->text('pertanyaan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pertanyaan_kuesioners');
    }
}
