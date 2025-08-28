<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamListTable extends Migration
{
    public function up()
    {
        Schema::create('team_list', function (Blueprint $table) {
            $table->id('team_id');
            $table->string('school_name')->unique(); // mencegah duplikat
            $table->string('competition');
            $table->string('season');
            $table->string('series');
            $table->enum('team_category', ['Basket Putra', 'Basket Putri', 'Dancer']);
            $table->string('registered_by');
            $table->enum('locked_status', ['unlocked', 'locked'])->default('unlocked');
            $table->enum('verification_status', ['unverified', 'verified'])->default('unverified');
            $table->string('recommendation_letter')->nullable();
            $table->string('koran')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_list');
    }
}

