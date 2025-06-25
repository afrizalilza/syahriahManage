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
        Schema::create('santri_biaya_bebas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('santri_id');
            $table->unsignedBigInteger('biaya_id');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('santri_id')->references('id')->on('santris')->onDelete('cascade');
            $table->foreign('biaya_id')->references('id')->on('biayas')->onDelete('cascade');
            $table->unique(['santri_id', 'biaya_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('santri_biaya_bebas');
    }
};
