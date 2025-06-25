<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasukans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kas_id');
            $table->date('tanggal');
            $table->string('nama');
            $table->integer('nominal');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemasukans');
    }
};
