<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->unique(['nama_biaya', 'unit'], 'biayas_nama_biaya_unit_unique');
        });
    }

    public function down()
    {
        Schema::table('biayas', function (Blueprint $table) {
            $table->dropUnique('biayas_nama_biaya_unit_unique');
        });
    }
};
