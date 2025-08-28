<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('match_data', function (Blueprint $table) {
            $table->string('series_name')->after('status');
        });
    }

    public function down()
    {
        Schema::table('match_data', function (Blueprint $table) {
            $table->dropColumn('series_name');
        });
    }
};
