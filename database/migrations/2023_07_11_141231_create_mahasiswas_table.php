<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswasTable extends Migration
{
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->unsignedBigInteger('subprodi_id');
            $table->foreign('subprodi_id')->references('id')->on('sub_prodis')->restrictOnDelete();
            $table->char('nim', 8)->unique();
            $table->string('telp', 14)->unique();
            $table->text('alamat');
            $table->string('foto', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mahasiswas');
    }
}
