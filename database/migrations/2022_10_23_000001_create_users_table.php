<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->nullable();
            $table->string('nama');
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('telp')->unique()->nullable();
            $table->string('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->foreign('prodi_id')->references('id')->on('prodis')->restrictOnDelete();
            $table->unsignedBigInteger('subprodi_id')->nullable();
            $table->foreign('subprodi_id')->references('id')->on('sub_prodis')->restrictOnDelete();
            $table->enum('role', ['dev', 'admin', 'kalab', 'laboran', 'peminjam', 'web', 'tamu']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
