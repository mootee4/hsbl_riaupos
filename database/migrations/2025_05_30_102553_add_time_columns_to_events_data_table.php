<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeColumnsToEventsDataTable extends Migration
{
    public function up()
    {
        Schema::table('events_data', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('start_date');
            $table->time('end_time')->nullable()->after('end_date');
        });
    }

    public function down()
    {
        Schema::table('events_data', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
}
