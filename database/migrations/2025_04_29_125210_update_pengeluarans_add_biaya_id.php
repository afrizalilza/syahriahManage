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
            // Tambah kolom biaya_id (nullable dulu biar aman)
            $table->unsignedBigInteger('biaya_id')->nullable()->after('id');
            // Tambah foreign key ke biayas
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
        });
    }
};
