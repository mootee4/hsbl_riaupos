<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchResultsTable extends Migration
{
    public function up()
    {
        Schema::create('match_results', function (Blueprint $table) {
            $table->id();
            $table->date('match_date');
            $table->string('competition');
            $table->string('phase');
            $table->unsignedBigInteger('team1_id');
            $table->unsignedBigInteger('team2_id');
            $table->integer('score_1')->nullable();
            $table->integer('score_2')->nullable();
            $table->string('scoresheet')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_results');
    }
}
