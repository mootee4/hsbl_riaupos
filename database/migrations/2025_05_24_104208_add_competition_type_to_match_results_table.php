<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('match_results', function (Blueprint $table) {
            $table->string('competition_type')->nullable()->after('competition');
        });
    }

    public function down()
    {
        Schema::table('match_results', function (Blueprint $table) {
            $table->dropColumn('competition_type');
        });
    }
};
