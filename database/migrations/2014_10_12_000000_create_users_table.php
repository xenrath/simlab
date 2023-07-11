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
            $table->string('username')->unique();
            $table->string('nama');
            $table->string('password');
            $table->string('telp')->unique()->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->enum('role', ['dev', 'admin', 'kalab', 'laboran', 'peminjam', 'web']);
            $table->boolean('status')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
