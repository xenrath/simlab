<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTamusTable extends Migration
{
    public function up()
    {
        Schema::create('tamus', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 60);
            $table->string('telp', 14)->unique();
            $table->string('institusi', 60);
            $table->text('alamat');
            $table->text('keperluan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tamus');
    }
}
