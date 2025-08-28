<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('add_data', function (Blueprint $table) {
            $table->id();
            $table->string('season_name');
            $table->string('series_name');
            $table->string('competition');
            $table->string('phase');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('add_data');
    }
};

