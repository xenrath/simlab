<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabanKuesionersTable extends Migration
{
    public function up()
    {
        Schema::create('jawaban_kuesioners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjam_id');
            $table->foreign('peminjam_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('pertanyaankuesioner_id');
            $table->foreign('pertanyaankuesioner_id')->references('id')->on('pertanyaan_kuesioners')->restrictOnDelete();
            $table->string('jawaban');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jawaban_kuesioners');
    }
}
