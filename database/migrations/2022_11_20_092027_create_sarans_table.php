<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaransTable extends Migration
{
    public function up()
    {
        Schema::create('sarans', function (Blueprint $table) {
            $table->id();
            $table->text('saran');
            $table->enum('kategori', ['saran', 'kendala', 'ucapan']);
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sarans');
    }
}
