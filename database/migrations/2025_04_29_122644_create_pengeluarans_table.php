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
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['kas_id']);
            $table->dropColumn('kas_id');
            $table->unsignedBigInteger('biaya_id');
            $table->foreign('biaya_id')->references('id')->on('biayas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['biaya_id']);
            $table->dropColumn('biaya_id');
            $table->unsignedBigInteger('kas_id');
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('cascade');
        });
    }
};
