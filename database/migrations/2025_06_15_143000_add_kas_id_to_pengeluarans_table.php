<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->unsignedBigInteger('kas_id')->nullable()->after('biaya_id');
            $table->foreign('kas_id')->references('id')->on('kas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropForeign(['kas_id']);
            $table->dropColumn('kas_id');
        });
    }
};
