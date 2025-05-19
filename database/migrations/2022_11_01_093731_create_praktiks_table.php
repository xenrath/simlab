<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePraktiksTable extends Migration
{
    public function up()
    {
        Schema::create('praktiks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tempat_id');
            $table->foreign('tempat_id')->references('id')->on('tempats')->restrictOnDelete();
            $table->enum('kategori', ['normal', 'estafet']);
            $table->string('nama');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('praktiks');
    }
}
