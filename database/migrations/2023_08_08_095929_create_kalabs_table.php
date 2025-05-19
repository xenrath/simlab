<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKalabsTable extends Migration
{
    public function up()
    {
        Schema::create('kalabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->char('nipy', 18)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kalabs');
    }
}
